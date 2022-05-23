<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface VoteInterface extends EntityInterface
{
    public const TYPE_CAT_PER_ONE = 1;
    public const TYPE_CAT_PER_TWO = 2;

    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): void;

    public function getVoteType(): VoteTypeInterface;

    public function setVoteType(VoteTypeInterface $voteType): void;

    public function getProject(): ProjectInterface;

    public function setProject(ProjectInterface $project): void;
}
