<?php

declare(strict_types=1);

namespace App\Entity;

interface OfflineVoteInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];
}
