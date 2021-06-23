<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserPreference;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;

final class UserRepository extends EntityRepository
{
    public function getPrizeNotificationList(int $limit)
    {
        $qb = $this->createQueryBuilder('u')
                   ->innerJoin(UserPreference::class, 'up', Join::WITH, 'up.user = u.id')
                   ->where('u.active = :active')
                   ->andWhere('up.prize = :prize')
                   ->andWhere('up.prizeHash IS NULL')
                   ->setParameter('active', true)
                   ->setParameter('prize', 0)
                   ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
