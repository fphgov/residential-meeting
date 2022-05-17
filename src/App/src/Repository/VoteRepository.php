<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\User;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Throwable;

class VoteRepository extends EntityRepository
{
    /** @return mixed|int */
    public function numberOfVotes(int $id)
    {
        // IMPLEMENT ME

        return 0;
        // $qb = $this->createQueryBuilder('v')
        //            ->select('COUNT(1)')
        //            ->where('v.projectCare = :id')
        //            ->orWhere('v.projectGreen = :id')
        //            ->orWhere('v.projectWhole = :id')
        //            ->setParameter('id', $id);

        // try {
        //     return (int) $qb->getQuery()->getSingleScalarResult();
        // } catch (Throwable $th) {
        // }

        // return 0;
    }

    public function checkExistsVoteInCampaign(User $user, Campaign $campaign): bool
    {
        $qb = $this->createQueryBuilder('v')
                ->select('COUNT(1)')
                ->innerJoin(Project::class, 'p', Join::WITH, 'p.id. = v.project')
                ->innerJoin(Campaign::class, 'c', Join::WITH, 'c.id. = p.campaign')
                ->where('v.user = :user')
                ->andWhere('c.id = :campaignId')
                ->setParameter('user', $user)
                ->setParameter('campaignId', $campaign->getId());

        try {
            return (int) $qb->getQuery()->getSingleScalarResult() > 0;
        } catch (Throwable $th) {
        }

        return false;
    }
}
