<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\WorkflowStateInterface;
use Doctrine\ORM\EntityRepository;

use function implode;

final class IdeaRepository extends EntityRepository
{
    public function getWorkflowStates(string $campaign = '')
    {
        $qb = $this->createQueryBuilder('i');

        $qb
            ->where('i.workflowState NOT IN (:disableWorkflowStates)')
            ->groupBy('i.workflowState');

        if ($campaign != '') {
            $qb->andWhere('i.campaign IN (:campaign)');
            $qb->setParameter('campaign', $campaign);
        }

        $qb->setParameter('disableWorkflowStates', [
            WorkflowStateInterface::STATUS_RECEIVED,
            WorkflowStateInterface::STATUS_USER_DELETED,
            WorkflowStateInterface::STATUS_TRASH,
        ]);

        $workflowStates = [];

        foreach ($qb->getQuery()->getResult() as $idea) {
            $workflowStates[] = $idea->getWorkflowState();
        }

        return $workflowStates;
    }
}
