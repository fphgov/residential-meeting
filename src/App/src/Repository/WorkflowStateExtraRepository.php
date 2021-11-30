<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class WorkflowStateExtraRepository extends EntityRepository
{
    public function getAllWorkflowStateExtra(): array
    {
        $workflowStateExtras = $this->findAll();

        $normalizedWorkFlowStateExtras = [];
        foreach ($workflowStateExtras as $workflowStateExtra) {
            $normalizedWorkFlowStateExtras[] = $workflowStateExtra->normalizer(null, ['groups' => 'option']);
        }

        return $normalizedWorkFlowStateExtras;
    }
}
