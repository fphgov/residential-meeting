<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;

interface ProjectServiceInterface
{
    public function addProject(UserInterface $submitter, array $filteredParams): ?ProjectInterface;

    public function getRepository(): EntityRepository;
}
