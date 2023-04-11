<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountInterface;
use App\Entity\Newsletter;
use App\Entity\Notification;
use App\Entity\Question;
use App\Entity\Setting;
use App\Entity\Vote;
use App\Entity\VoteInterface;
use App\Exception\AccountNotVotableException;
use App\Exception\CloseCampaignException;
use App\Service\MailServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class VoteService implements VoteServiceInterface
{
    /** @var EntityRepository */
    private $settingRepository;

    /** @var EntityRepository */
    private $questionRepository;

    /** @var EntityRepository */
    private $notificationRepository;

    /** @var EntityRepository */
    private $newsletterRepository;

    public function __construct(
        private array $config,
        private EntityManagerInterface $em,
        private MailServiceInterface $mailService
    ) {
        $this->config                 = $config;
        $this->em                     = $em;
        $this->mailService            = $mailService;
        $this->questionRepository     = $this->em->getRepository(Question::class);
        $this->settingRepository      = $this->em->getRepository(Setting::class);
        $this->notificationRepository = $this->em->getRepository(Notification::class);
        $this->newsletterRepository   = $this->em->getRepository(Newsletter::class);
    }

    private function createVote(
        Question $question,
        ?bool $answer
    ): VoteInterface {
        $vote = new Vote();

        $vote->setQuestion($question);
        $vote->setAnswer($answer);

        $this->em->persist($vote);

        return $vote;
    }

    public function voting(
        AccountInterface $account,
        array $filteredData
    ): void {
        if ($this->settingRepository->getIsCloseVote()) {
            throw new CloseCampaignException('No votable');
        }

        if ($account->getVoted()) {
            throw new AccountNotVotableException('Already voted ' . $account->geAuthCode());
        }

        foreach ($filteredData['questions'] as $id => $answer) {
            $question = $this->questionRepository->find($id);

            $parsedAnswer = $this->parse($answer);

            $this->createVote($question, $parsedAnswer);
        }

        $successNotification = null;

        if (isset($filteredData['email'])) {
            $email = $this->notificationRepository->findOneBy([
                'email' => $filteredData['email'],
            ]);

            if (! $email) {
                $notification = new Notification();
                $notification->setEmail($filteredData['email']);

                $email = $notification;

                $this->em->persist($notification);
            }

            $successNotification = $email;
        }

        if (isset($filteredData['email']) && isset($filteredData['newsletter'])) {
            $email = $this->newsletterRepository->findOneBy([
                'email' => $filteredData['email'],
            ]);

            if (! $email) {
                $newsletter = new Newsletter();
                $newsletter->setEmail($filteredData['email']);

                $this->em->persist($newsletter);
            }
        }

        $account->setPrivacy($this->parse($filteredData['privacy']));
        $account->setVoted(true);
        $account->setUpdatedAt(new DateTime());

        $this->em->flush();

        if ($successNotification !== null) {
            $this->successVote($successNotification);
        }
    }

    private function parse(mixed $answer): ?bool
    {
        if ($answer === null || $answer === "null") {
            return null;
        }

        if ($answer === true || $answer === "true" || $answer === "on" || $answer === "yes") {
            return true;
        }

        return false;
    }

    private function successVote(Notification $successNotification): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
        ];

        $this->mailService->send('vote-success', $tplData, $successNotification);
    }
}
