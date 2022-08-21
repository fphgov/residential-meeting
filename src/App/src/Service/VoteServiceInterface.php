<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use App\Entity\VoteTypeInterface;
use Doctrine\ORM\EntityRepository;

interface VoteServiceInterface
{
    public function addOfflineVote(
        UserInterface $user,
        int $projectId,
        int $type,
        int $voteCount
    ): void;

    public function voting(
        UserInterface $user,
        VoteTypeInterface $voteType,
        array $projects
    ): void;

    public function getRepository(): EntityRepository;

    public function getVoteablesProjects(?string $rand = null): array;
}
