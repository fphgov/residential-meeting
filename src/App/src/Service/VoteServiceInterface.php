<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use App\Entity\VoteInterface;
use App\Entity\VoteTypeInterface;
use Doctrine\ORM\EntityRepository;

interface VoteServiceInterface
{
    public function addOfflineVote(
        UserInterface $user,
        VoteTypeInterface $voteType,
        ProjectInterface $project,
        int $voteCount
    ): void;

    public function voting(
        UserInterface $user,
        VoteTypeInterface $voteType,
        array $projects
    ): void;

    public function getRepository(): EntityRepository;
}
