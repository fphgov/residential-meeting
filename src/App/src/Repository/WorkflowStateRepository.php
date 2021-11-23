<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class WorkflowStateRepository extends EntityRepository
{
    public function getAllWorkflowState(): array
    {
        $workflowStates = $this->findAll();

        $normalizedWorkFlowStates = [];
        foreach ($workflowStates as $workflowState) {
            $normalizedWorkFlowStates[] = $workflowState->normalizer(null, ['groups' => 'option']);
        }

        return $normalizedWorkFlowStates;
    }
}
