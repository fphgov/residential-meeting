<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OfflineVote;
use App\Entity\PhaseInterface;
use App\Entity\Project;
use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use App\Entity\Vote;
use App\Entity\VoteInterface;
use App\Entity\VoteType;
use App\Entity\VoteTypeInterface;
use App\Exception\NoExistsAllProjectsException;
use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use App\Service\VoteValidationService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use function count;
use function strtolower;

final class VoteService implements VoteServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var EntityRepository */
    private $voteRepository;

    /** @var PhaseServiceInterface */
    private $phaseService;

    /** @var MailServiceInterface */
    private $mailService;

    /** @var EntityRepository */
    private $projectRepository;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        PhaseServiceInterface $phaseService,
        MailServiceInterface $mailService,
        VoteValidationService $voteValidationService
    ) {
        $this->config                = $config;
        $this->em                    = $em;
        $this->phaseService          = $phaseService;
        $this->mailService           = $mailService;
        $this->voteValidationService = $voteValidationService;
        $this->projectRepository     = $this->em->getRepository(Project::class);
        $this->voteRepository        = $this->em->getRepository(Vote::class);
    }

    public function addOfflineVote(
        UserInterface $user,
        VoteTypeInterface $voteType,
        ProjectInterface $project,
        int $voteCount
    ): void {
        $date = new DateTime();

        for ($i = 0; $i < $voteCount; $i++) {
            $this->createOfflineVote($user, $project, $date);
        }

        $this->em->flush();
    }

    private function createOfflineVote(
        UserInterface $user,
        ProjectInterface $project,
        DateTime $date,
        int $type
    ): VoteInterface {
        $vote = new OfflineVote();

        $vote->setUser($user);
        $vote->setProject($project);
        $vote->setVoteType(
            $this->em->getReference(VoteType::class, $type)
        );
        $vote->setCreatedAt($date);
        $vote->setUpdatedAt($date);

        $this->em->persist($vote);

        return $vote;
    }

    private function createOnlineVote(
        UserInterface $user,
        ProjectInterface $project,
        VoteTypeInterface $voteType,
        DateTime $date
    ): VoteInterface {
        $vote = new Vote();

        $vote->setUser($user);
        $vote->setProject($project);
        $vote->setVoteType($voteType);
        $vote->setCreatedAt($date);
        $vote->setUpdatedAt($date);

        $user->addVote($vote);

        $this->em->persist($vote);

        return $vote;
    }

    public function voting(
        UserInterface $user,
        VoteTypeInterface $voteType,
        array $projects
    ): void {
        $phase = $this->phaseService->phaseCheck(PhaseInterface::PHASE_VOTE);

        $dbProjects = $this->projectRepository->findBy([
            'id' => $projects,
        ]);

        if (count($dbProjects) !== count($projects)) {
            throw new NoExistsAllProjectsException('There are 1 or more ideas specified');
        }

        $this->voteValidationService->validation(
            $user,
            $phase,
            $voteType,
            $dbProjects
        );

        $date = new DateTime();

        $votes = [];
        foreach ($dbProjects as $project) {
            $votes[] = $this->createOnlineVote($user, $project, $voteType, $date);
        }

        $this->em->flush();

        $this->successVote($user, $votes);
    }

    /**
     * @param array[]|VoteInterface $votes
     **/
    private function successVote(UserInterface $user, array $votes): void
    {
        $projects = [];

        foreach ($votes as $vote) {
            $projects[] = [
                'title'        => $vote->getProject()->getTitle(),
                'campaignName' => $vote->getProject()->getCampaignTheme()->getName(),
                'projectType'  => strtolower($vote->getProject()->getProjectType()->getTitle()),
            ];
        }

        $tplData = [
            'name'             => $user->getFirstname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'projects'         => $projects,
        ];

        $this->mailService->send('vote-success', $tplData, $user);
    }

    public function getVoteablesProjects(?string $rand = null): array
    {
        $phase = $this->phaseService->phaseCheck(PhaseInterface::PHASE_VOTE);

        $projects = $this->projectRepository->getVoteables($phase->getCampaign(), $rand);

        $normalizedProjects = [];
        foreach ($projects as $project) {
            $normalizedProjects[] = $project->normalizer(null, ['groups' => 'vote_list']);
        }

        return $normalizedProjects;
    }

    public function getRepository(): EntityRepository
    {
        return $this->voteRepository;
    }
}
