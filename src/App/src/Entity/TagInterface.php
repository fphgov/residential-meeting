<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntityInterface;

interface TagInterface extends EntityInterface, EntityActiveInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setName(string $name): void;

    public function getName(): string;
}
