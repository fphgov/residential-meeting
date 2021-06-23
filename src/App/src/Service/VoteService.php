<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OfflineVote;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Vote;
use App\Service\MailQueueServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\Log\Logger;
use Mail\MailAdapter;
use Throwable;

use function error_log;

final class VoteService implements VoteServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Logger */
    private $audit;

    /** @var MailAdapter */
    private $mailAdapter;

    /** @var MailQueueServiceInterface */
    private $mailQueueService;

    /** @var EntityRepository */
    private $voteRepository;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        Logger $audit,
        MailAdapter $mailAdapter,
        MailQueueServiceInterface $mailQueueService
    ) {
        $this->config           = $config;
        $this->em               = $em;
        $this->audit            = $audit;
        $this->mailAdapter      = $mailAdapter;
        $this->mailQueueService = $mailQueueService;
        $this->voteRepository   = $this->em->getRepository(Vote::class);
    }

    public function addOfflineVote(User $user, array $filteredParams): void
    {
        $date = new DateTime();

        $project = $this->em->getReference(Project::class, $filteredParams['project']);
        $count   = (int) $filteredParams['voteCount'];

        for ($i = 0; $i < $count; $i++) {
            $this->createOfflineVote($user, $project, $date);
        }

        $this->em->flush();
    }

    private function createOfflineVote(User $user, Project $project, DateTime $date): void
    {
        $vote = new OfflineVote();

        $vote->setUser($user);
        $vote->setProject($project);
        $vote->setCreatedAt($date);
        $vote->setUpdatedAt($date);

        $this->em->persist($vote);
    }

    public function voting(User $user, array $filteredParams): Vote
    {
        $date = new DateTime();

        $vote = new Vote();

        $care  = $this->em->getReference(Project::class, $filteredParams['rk_vote_CARE']);
        $green = $this->em->getReference(Project::class, $filteredParams['rk_vote_GREEN']);
        $whole = $this->em->getReference(Project::class, $filteredParams['rk_vote_WHOLE']);

        $vote->setUser($user);
        $vote->setProjectCare($care);
        $vote->setProjectGreen($green);
        $vote->setProjectWhole($whole);
        $vote->setCreatedAt($date);
        $vote->setUpdatedAt($date);

        $this->em->persist($vote);
        $this->em->flush();

        $this->successVote($user, $vote);

        return $vote;
    }

    private function successVote(User $user, Vote $vote)
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->message->addTo($user->getEmail());
            $this->mailAdapter->message->setSubject('Köszönjük szavazatát!');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'votes'            => [
                    'CARE'  => [
                        'title' => $vote->getProjectCare()->getTitle(),
                    ],
                    'GREEN' => [
                        'title' => $vote->getProjectGreen()->getTitle(),
                    ],
                    'WHOLE' => [
                        'title' => $vote->getProjectWhole()->getTitle(),
                    ],
                ],
            ];

            $this->mailAdapter->setTemplate('email/vote-success', $tplData);

            $this->mailQueueService->add($this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Vote success notification no added to MailQueueService', [
                'extra' => $user->getId(),
            ]);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->voteRepository;
    }
}
