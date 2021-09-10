<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface WorkflowStateInterface extends EntityInterface
{
    public const STATUS_RECEIVED  = 100;
    public const STATUS_PUBLISHED = 101;
    public const STATUS_REJECTED  = 151;

    public const STATUSES = [
        self::STATUS_RECEIVED  => 'Beküldött',
        self::STATUS_PUBLISHED => 'Közzétéve',
        self::STATUS_REJECTED  => 'Elutasított',
    ];

    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];
}
