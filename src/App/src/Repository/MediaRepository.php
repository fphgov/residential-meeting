<?php

declare(strict_types=1);

namespace App\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

final class MediaRepository extends EntityRepository
{
    public function getExpiredMedias(DateTime $date): array
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->where('m.expirationDate <= :date')
            ->orWhere('m.expirationDate IS NULL')
            ->setParameters([
                'date' => $date,
            ]);

        return $qb->getQuery()->getResult();
    }
}
