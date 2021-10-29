<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface VoteInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): void;

    public function setProjectCare(ProjectInterface $projectCare): void;

    public function getProjectCare(): ProjectInterface;

    public function setProjectGreen(ProjectInterface $projectGreen): void;

    public function getProjectGreen(): ProjectInterface;

    public function setProjectWhole(ProjectInterface $projectWhole): void;

    public function getProjectWhole(): ProjectInterface;
}
