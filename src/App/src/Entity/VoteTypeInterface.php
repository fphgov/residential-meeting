<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntitySimpleInterface;

interface VoteTypeInterface extends EntitySimpleInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getName(): string;

    public function setName(string $name): void;
}
