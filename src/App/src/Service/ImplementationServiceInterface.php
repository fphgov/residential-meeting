<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ImplementationInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;

interface ImplementationServiceInterface
{
    public function addImplementation(
        UserInterface $submitter,
        array $filteredParams
    ): void;

    public function modifyImplementation(
        ImplementationInterface $implementation,
        array $filteredParams
    ): void;

    public function deleteImplementation(
        UserInterface $submitter,
        ImplementationInterface $implementation
    ): void;

    public function getRepository(): EntityRepository;
}
