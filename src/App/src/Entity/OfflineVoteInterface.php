<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface OfflineVoteInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): void;

    public function setProject(ProjectInterface $project): void;

    public function getProject(): ProjectInterface;
}
