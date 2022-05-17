<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OfflineVote;
use App\Entity\PhaseInterface;
use App\Entity\Project;
use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use App\Entity\Settings;
use App\Entity\Vote;
use App\Entity\VoteType;
use App\Entity\VoteTypeInterface;
use App\Entity\VoteInterface;
use App\Exception\VoteUserExistsException;
use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class VoteValidationService implements VoteValidationServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var EntityRepository */
    private $voteRepository;

    /** @var EntityRepository */
    private $settingsRepository;

    public function __construct(
        array $config,
        EntityManagerInterface $em
    ) {
        $this->em                 = $em;
        $this->voteRepository     = $this->em->getRepository(Vote::class);
        $this->settingsRepository = $this->em->getRepository(Settings::class);
    }

    public function validation(
        UserInterface $user,
        VoteTypeInterface $voteType,
        array $projects
    ): void {
        // 1. Felhasználó szavazott-e már ebben a kampányban
        // 2. Tipus alapján definiálni kell a validálás módszerét
        // 3. Ellenőrizni, hogy az összes projekt létezik
        // 4A. Normál típus esetén 3 projekt
        // 4B. Nem normál típus esetén kis, nagy kategória

        $existsVote = $this->voteRepository->checkExistsVoteInCampaign($user, $campaign);

        if ($existsVote) {
            throw new VoteUserExistsException('User already voted this campaign');
        }

        $voteType = $this->settingsRepository->findBy([
            'key' => 'vote-type'
        ]);

        if (! $voteType) {
            throw new VoteTypeNoExistsInDatabaseException('Vote type no exists in database');
        }

        if ($voteType === '1') {
            $this->validationNormal($user, $voteType, $projects);
        } elseif ($voteType === '2') {
            $this->validationBigCategory($user, $voteType, $projects);
        }
    }

    private function validationNormal(
        UserInterface $user,
        VoteTypeInterface $voteType,
        array $projects
    ): void
    {
        // Implement me
    }

    private function validationBigCategory(
        UserInterface $user,
        VoteTypeInterface $voteType,
        array $projects
    ): void
    {
        // Implement me
    }
}
