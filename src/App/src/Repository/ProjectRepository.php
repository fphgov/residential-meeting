<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class ProjectRepository extends EntityRepository
{
    public function getForSelection(?int $campaignTheme = null)
    {
        $filteredProjects = $this->findAll();

        $selectables = [];
        foreach ($filteredProjects as $project) {
            if (! isset($selectables[$project->getCampaignTheme()->getId()])) {
                $selectables[$project->getCampaignTheme()->getId()] = [
                    'id'    => $project->getCampaignTheme()->getId(),
                    'name'  => $project->getCampaignTheme()->getName(),
                    'code'  => $project->getCampaignTheme()->getCode(),
                    'elems' => []
                ];
            }

            $selectables[$project->getCampaignTheme()->getId()]['elems'][] = [
                'id'   => $project->getId(),
                'name' => $project->getTitle(),
            ];
        }

        return $selectables;
    }
}
