<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MailLog;
use App\Entity\UserPreference;
use App\Entity\Vote;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

final class UserRepository extends EntityRepository
{
    public function getActiveUsers(): array
    {
        return $this->findBy([
            'active' => true,
            'role'   => 'user',
        ]);
    }

    public function noActivatedUsers(int $hour): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.active = :active')
            ->andWhere('u.updatedAt < DATE_SUB(NOW(), ' . $hour . ', \'HOUR\')')
            ->setParameter('active', false)
            ->orderBy('u.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getPrizeNotificationList(
        ?int $limit = null,
        string $emailName,
        bool $hasVote = true
    ): array {
        $qb = $this->getNotificationQuery($emailName);

        if ($hasVote) {
            $qb->innerJoin(Vote::class, 'v', Join::WITH, 'v.user = u.id');
        }

        $qb->andWhere('up.prize = :prize')->setParameter('prize', false);
        $qb->andWhere('up.prizeHash IS NULL');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    private function getNotificationQuery(string $emailName): QueryBuilder
    {
        $qbMail = $this->createQueryBuilder('u');
        $qbMail->select('u.id')
            ->leftJoin(MailLog::class, 'ml', Join::WITH, 'ml.user = u.id')
            ->where('ml.name = :emailName')
            ->setParameter('emailName', $emailName);

        $qb = $this->createQueryBuilder('u');

        $qb->innerJoin(UserPreference::class, 'up', Join::WITH, 'up.user = u.id');
        $qb->leftJoin(MailLog::class, 'ml', Join::WITH, 'ml.user = u.id');

        $qb->where('u.active = :active')
            ->andWhere('u.role = :role')
            ->andWhere('up.campaignEmail = :campaignEmail')
            ->andWhere('u.id NOT IN (:disableIds)')
            ->setParameter('active', true)
            ->setParameter('role', 'user')
            ->setParameter('campaignEmail', true)
            ->setParameter('disableIds', $qbMail->getDQL())
            ->orderBy('u.id', 'ASC');

        return $qb;
    }
}
