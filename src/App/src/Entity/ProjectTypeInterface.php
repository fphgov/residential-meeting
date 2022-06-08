<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntitySimpleInterface;

interface ProjectTypeInterface extends EntitySimpleInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [
        'createdAt',
        'updatedAt',
    ];

    public const IDEA_NORMAL = 1;
    public const IDEA_SMALL  = 2;
    public const IDEA_BIG    = 3;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;
}
