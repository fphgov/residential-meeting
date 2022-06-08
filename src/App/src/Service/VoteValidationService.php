<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CampaignInterface;
use App\Entity\OfflineVote;
use App\Entity\PhaseInterface;
use App\Entity\Project;
use App\Entity\ProjectInterface;
use App\Entity\ProjectTypeInterface;
use App\Entity\UserInterface;
use App\Entity\Setting;
use App\Entity\Vote;
use App\Entity\VoteType;
use App\Entity\VoteTypeInterface;
use App\Entity\VoteInterface;
use App\Exception\VoteUserExistsException;
use App\Exception\MissingVoteTypeAndCampaignCategoriesException;
use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use function array_keys;
use function array_key_exists;

final class VoteValidationService implements VoteValidationServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var EntityRepository */
    private $voteRepository;

    /** @var EntityRepository */
    private $settingRepository;

    /** @var EntityRepository */
    private $projectRepository;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em                = $em;
        $this->voteRepository    = $this->em->getRepository(Vote::class);
        $this->settingRepository = $this->em->getRepository(Setting::class);
        $this->projectRepository = $this->em->getRepository(Project::class);
    }

    public function validation(
        UserInterface $user,
        PhaseInterface $phase,
        VoteTypeInterface $voteType,
        array $projects
    ): void {
        $existsVote = $this->voteRepository->checkExistsVoteInCampaign($user, $phase->getCampaign());

        if ($existsVote) {
            throw new VoteUserExistsException('User already voted this campaign');
        }

        $voteType = $this->settingRepository->findOneBy([
            'key' => 'vote-type'
        ]);

        if (! $voteType) {
            throw new VoteTypeNoExistsInDatabaseException('Vote type no exists in database');
        }

        if ($voteType->getValue() === '1') {
            $this->validationNormal($user, $projects);
        } elseif ($voteType->getValue() === '2') {
            $this->validationBigCategory($user, $phase->getCampaign(), $projects);
        }
    }

    private function validationNormal(
        UserInterface $user,
        array $projects
    ): void
    {
        $types = [];
        foreach ($projects as $project) {
            $types[$project->getCampaignTheme()->getId() . '-' . $project->getProjectType()->getId()] = $project;
        }

        if (count($types) !== count($projects)) {
            throw new MissingVoteTypeAndCampaignCategoriesException('There are no ideas in all categories and types');
        }

        $campaignThemes = $campaign->getCampaignThemes();

        $testTypes = [];
        foreach ($campaignThemes as $campaignThemeId) {
            $testTypes[] = $campaignThemeId . '-' . ProjectTypeInterface::IDEA_NORMAL;
        }

        $hasAll = true;
        foreach (array_keys($types) as $type) {
            if (! in_array($type, $testTypes, true)) {
                $hasAll = false;
            }
        }

        if ($hasAll === false) {
            throw new MissingVoteTypeAndCampaignCategoriesException('There are no ideas in all categories and types');
        }
    }

    private function validationBigCategory(
        UserInterface $user,
        CampaignInterface $campaign,
        array $projects
    ): void
    {
        $types = [];
        foreach ($projects as $project) {
            $types[$project->getCampaignTheme()->getId() . '-' . $project->getProjectType()->getId()] = $project;
        }

        if (count($types) !== count($projects)) {
            throw new MissingVoteTypeAndCampaignCategoriesException('There are no ideas in all categories and types');
        }

        $campaignThemes = $campaign->getCampaignThemes();

        $testTypes = [];
        foreach ($campaignThemes as $campaignThemeId) {
            $testTypes[] = $campaignThemeId . '-' . ProjectTypeInterface::IDEA_SMALL;
            $testTypes[] = $campaignThemeId . '-' . ProjectTypeInterface::IDEA_BIG;
        }

        $hasAll = true;
        foreach (array_keys($types) as $type) {
            if (! in_array($type, $testTypes, true)) {
                $hasAll = false;
            }
        }

        if ($hasAll === false) {
            throw new MissingVoteTypeAndCampaignCategoriesException('There are no ideas in all categories and types');
        }
    }
}
