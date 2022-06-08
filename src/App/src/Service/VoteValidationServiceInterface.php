<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProjectInterface;
use App\Entity\PhaseInterface;
use App\Entity\UserInterface;
use App\Entity\VoteInterface;
use App\Entity\VoteTypeInterface;
use Doctrine\ORM\EntityRepository;

interface VoteValidationServiceInterface
{
    public function validation(
        UserInterface $user,
        PhaseInterface $phase,
        VoteTypeInterface $voteType,
        array $projects
    ): void;
}
