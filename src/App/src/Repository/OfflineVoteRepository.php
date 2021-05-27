<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class OfflineVoteRepository extends EntityRepository
{
    /** @return mixed|int */
    public function numberOfVotes(int $id)
    {
        $qb = $this->createQueryBuilder('v')
                   ->select('COUNT(1)')
                   ->where('v.projectCare = :id')
                   ->orWhere('v.projectGreen = :id')
                   ->orWhere('v.projectWhole = :id')
                   ->setParameter('id', $id);

        try {
            return (int)$qb->getQuery()->getSingleScalarResult();
        } catch (\Throwable $th) {

        }

        return 0;
    }
}
