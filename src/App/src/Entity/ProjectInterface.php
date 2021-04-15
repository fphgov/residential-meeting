<?php

declare(strict_types=1);

namespace App\Entity;

interface ProjectInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'updatedAt',
    ];
}
