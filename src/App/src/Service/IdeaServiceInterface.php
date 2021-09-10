<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\IdeaInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;

interface IdeaServiceInterface
{
    public function addIdea(
        UserInterface $submitter,
        array $filteredParams
    ): ?IdeaInterface;

    public function getRepository(): EntityRepository;
}
