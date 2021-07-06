<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserPreference;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

final class UserRepository extends EntityRepository
{
    public function getPrizeNotificationList(int $limit): array
    {
        $qb = $this->createQueryBuilder('u')
                   ->innerJoin(UserPreference::class, 'up', Join::WITH, 'up.user = u.id')
                   ->where('u.active = :active')
                   ->andWhere('up.prize = :prize')
                   ->andWhere('up.prizeHash IS NULL')
                   ->andWhere('up.prizeNotified = :prizeNotified')
                   ->setParameter('active', true)
                   ->setParameter('prize', false)
                   ->setParameter('prizeNotified', false)
                   ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
