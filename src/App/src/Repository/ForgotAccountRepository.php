<?php

declare(strict_types=1);

namespace App\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class ForgotAccountRepository extends EntityRepository
{
    public function getExpiredForgotAccounts(DateTime $date): array
    {
        $qb = $this->createQueryBuilder('fa');

        $qb
            ->where('fa.expirationDate <= :date')
            ->setParameters([
                'date' => $date,
            ]);

        return $qb->getQuery()->getResult();
    }
}
