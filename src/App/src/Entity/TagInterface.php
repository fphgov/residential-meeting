<?php

declare(strict_types=1);

namespace App\Entity;

interface TagInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];
}
