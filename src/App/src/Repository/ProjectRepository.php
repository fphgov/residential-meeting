<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CampaignInterface;
use App\Entity\WorkflowStateInterface;
use Doctrine\ORM\EntityRepository;

final class ProjectRepository extends EntityRepository
{
    public function getForSelection(?int $campaignTheme = null): array
    {
        $filteredProjects = $this->findAll();

        $selectables = [];
        foreach ($filteredProjects as $project) {
            if (! isset($selectables[$project->getCampaignTheme()->getId()])) {
                $selectables[$project->getCampaignTheme()->getId()] = [
                    'id'    => $project->getCampaignTheme()->getId(),
                    'name'  => $project->getCampaignTheme()->getName(),
                    'code'  => $project->getCampaignTheme()->getCode(),
                    'elems' => [],
                ];
            }

            $selectables[$project->getCampaignTheme()->getId()]['elems'][] = [
                'id'   => $project->getId(),
                'name' => $project->getTitle(),
            ];
        }

        return $selectables;
    }

    public function getWorkflowStates(string $campaign = ''): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where('p.workflowState NOT IN (:disableWorkflowStates)')
            ->groupBy('p.workflowState');

        if ($campaign !== '') {
            $qb->andWhere('p.campaign IN (:campaign)');
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

    public function getVoteables(CampaignInterface $campaign, ?string $rand = null): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where('p.workflowState = :workflowState')
            ->andWhere('p.campaign = :campaign')
            ->setParameters([
                'workflowState' => WorkflowStateInterface::STATUS_VOTING_LIST,
                'campaign'      => $campaign,
            ]);

        if ($rand !== null) {
            $qb->orderBy('RAND(:rand)');
            $qb->setParameter('rand', $rand);
        }

        return $qb->getQuery()->getResult();
    }
}
