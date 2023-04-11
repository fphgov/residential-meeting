<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountInterface;
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

    public function __construct(
        private array $config,
        private EntityManagerInterface $em,
        private MailServiceInterface $mailService
    ) {
        $this->config             = $config;
        $this->em                 = $em;
        $this->mailService        = $mailService;
        $this->questionRepository = $this->em->getRepository(Question::class);
        $this->settingRepository  = $this->em->getRepository(Setting::class);
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

            $parsedAnswer = $this->parseAnswer($answer);

            $this->createVote($question, $parsedAnswer);
        }

        if ($filteredData['email']) {
            $account->setEmail($filteredData['email']);
        }

        if ($filteredData['newsletter']) {
            $account->setNewsletter(true);
        }

        $account->setPrivacy(true);
        $account->setVoted(true);
        $account->setUpdatedAt(new DateTime());

        $this->em->flush();

        $this->successVote($account);
    }

    private function parseAnswer(mixed $answer): ?bool
    {
        if ($answer === null || $answer === "null") {
            return null;
        }

        if ($answer === true || $answer === "true" || $answer === "on" || $answer === "yes") {
            return true;
        }

        return false;
    }

    private function successVote(AccountInterface $account): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
        ];

        $this->mailService->send('vote-success', $tplData, $account);
    }
}
