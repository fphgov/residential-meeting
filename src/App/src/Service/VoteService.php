<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\AccountInterface;
use App\Entity\Notification;
use App\Entity\Question;
use App\Entity\Setting;
use App\Entity\Vote;
use App\Entity\VoteInterface;
use App\Entity\Newsletter;
use App\Exception\AccountNotVotableException;
use App\Exception\CloseCampaignException;
use App\Service\AmpqServiceInterface;
use App\Service\NewsletterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\Log\Logger;
use Mail\Entity\SimpleNotification;

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
        private EntityManagerInterface $em,
        private AmpqServiceInterface $mq,
        private Logger $audit,
        private NewsletterServiceInterface $newsletterService
    ) {
        $this->em                     = $em;
        $this->mq                     = $mq;
        $this->newsletterService      = $newsletterService;
        $this->questionRepository     = $this->em->getRepository(Question::class);
        $this->settingRepository      = $this->em->getRepository(Setting::class);
        $this->notificationRepository = $this->em->getRepository(Notification::class);
        $this->newsletterRepository   = $this->em->getRepository(Newsletter::class);
    }

    private function createVote(
        Question $question,
        Account $account,
        ?bool $answer
    ): VoteInterface {
        $vote = new Vote();

        $vote->setQuestion($question);
        $vote->setAnswer($answer);
        $vote->setZipCode($account->getZipCode());

        $this->em->persist($vote);

        return $vote;
    }

    public function voting(
        AccountInterface $account,
        array $filteredData
    ): void {
        $this->checkVoteable($account);

        foreach ($filteredData['questions'] as $id => $answer) {
            $question = $this->questionRepository->find($id);

            $parsedAnswer = $this->parse($answer);

            $this->createVote($question, $account, $parsedAnswer);
        }

        $successNotification = null;

        if (isset($filteredData['email']) && ! empty($filteredData['email'])) {
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

        if (
            isset($filteredData['email']) &&
            !empty($filteredData['email']) &&
            isset($filteredData['newsletter']) &&
            $this->parse($filteredData['newsletter']) === true
        ) {
            $email = $this->newsletterRepository->findOneBy([
                'email' => $filteredData['email'],
            ]);

            if (!$email) {
                $newsletter = new Newsletter();
                $newsletter->setEmail($filteredData['email']);

                $this->em->persist($newsletter);
            }
        }

        $account->setVoted(true);
        $account->setZipCode(null);

        $this->em->flush();

        if ($successNotification !== null) {
            $simpleNotification = new SimpleNotification(
                $successNotification->getId(),
                $successNotification->getEmail(),
                'vote-success'
            );

            $this->mq->add('notification_queue', $simpleNotification);
        }
    }

    public function checkVoteable(AccountInterface $account): void
    {
        if ($this->settingRepository->getIsCloseVote()) {
            throw new CloseCampaignException('No votable');
        }

        if ($account->getVoted()) {
            throw new AccountNotVotableException('Already voted ' . $account->geAuthCode());
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
}
