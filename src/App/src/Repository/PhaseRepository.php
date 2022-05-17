<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Phase;
use DateTime;
use Doctrine\ORM\EntityRepository;

class PhaseRepository extends EntityRepository
{
    public function getCurrentPhase(): ?Phase
    {
        $currentDate = new DateTime();

        $qb = $this->createQueryBuilder('p');

        $qb
            ->where('p.start <= :currentDate')
            ->where('p.end > :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
