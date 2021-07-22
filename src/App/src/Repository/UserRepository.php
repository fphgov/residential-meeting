<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserPreference;
use App\Entity\Vote;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

final class UserRepository extends EntityRepository
{
    public function getPrizeNotificationList(int $limit): array
    {
        $qb = $this->createQueryBuilder('u')
                   ->innerJoin(UserPreference::class, 'up', Join::WITH, 'up.user = u.id')
                   ->innerJoin(Vote::class, 'v', Join::WITH, 'v.user = u.id')
                   ->where('u.active = :active')
                   ->andWhere('up.prize = :prize')
                   ->andWhere('up.prizeHash IS NULL')
                   ->andWhere('up.prizeNotified = :prizeNotified')
                   ->andWhere('up.campaignEmail = :campaignEmail')
                   ->setParameter('active', true)
                   ->setParameter('prize', false)
                   ->setParameter('prizeNotified', false)
                   ->setParameter('campaignEmail', true)
                   ->setMaxResults($limit)
                   ->orderBy('u.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function getPrizeNotificationListSec(int $limit): array
    {
        $qb = $this->createQueryBuilder('u')
                   ->innerJoin(UserPreference::class, 'up', Join::WITH, 'up.user = u.id')
                   ->innerJoin(Vote::class, 'v', Join::WITH, 'v.user = u.id')
                   ->where('u.active = :active')
                   ->andWhere('up.prizeNotified = :prizeNotified')
                   ->andWhere('up.prizeNotifiedSec = :prizeNotifiedSec')
                   ->andWhere('up.campaignEmail = :campaignEmail')
                   ->setParameter('active', true)
                   ->setParameter('prizeNotified', true)
                   ->setParameter('prizeNotifiedSec', false)
                   ->setParameter('campaignEmail', true)
                   ->setMaxResults($limit)
                   ->orderBy('u.id', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
