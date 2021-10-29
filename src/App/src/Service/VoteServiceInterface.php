<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\UserInterface;
use App\Entity\VoteInterface;
use Doctrine\ORM\EntityRepository;

interface VoteServiceInterface
{
    public function addOfflineVote(UserInterface $user, array $filteredParams): void;

    public function voting(UserInterface $user, array $filteredParams): VoteInterface;

    public function getRepository(): EntityRepository;
}
