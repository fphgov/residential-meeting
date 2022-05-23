<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OfflineVote;
use App\Entity\PhaseInterface;
use App\Entity\Project;
use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use App\Entity\Vote;
use App\Entity\VoteType;
use App\Entity\VoteTypeInterface;
use App\Entity\VoteInterface;
use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        PhaseServiceInterface $phaseService,
        MailServiceInterface $mailService
    ) {
        $this->config         = $config;
        $this->em             = $em;
        $this->phaseService   = $phaseService;
        $this->mailService    = $mailService;
        $this->voteRepository = $this->em->getRepository(Vote::class);
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
    ): VoteInterface
    {
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
    ): VoteInterface
    {
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
        $this->phaseService->phaseCheck(PhaseInterface::PHASE_VOTE);

        $date = new DateTime();

        $votes = [];
        foreach ($projects as $project) {
            $votes[] = $this->createOnlineVote($user, $project, $voteType, $date);
        }

        $this->em->flush();

        $this->successVote($user, $votes);
    }

    /**
     * @param UserInterface         $user
     * @param array[]|VoteInterface $votes
     *
     **/
    private function successVote(UserInterface $user, array $votes): void
    {
        $projects = [];

        foreach ($votes as $vote) {
            $projects[] = [
                'title'        => $vote->getProject()->getTitle(),
                'campaignName' => $vote->getProject()->getCampaignTheme()->getName(),
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

    public function getRepository(): EntityRepository
    {
        return $this->voteRepository;
    }
}
