<?php

declare(strict_types=1);

namespace App\Entity;

interface ProjectInterface {
    const STATUS_RECEIVED = 0;
    const STATUS_CHECKED  = 1;

    const STATUSES = [
        self::STATUS_RECEIVED => 'received',
        self::STATUS_CHECKED  => 'checked',
    ];
}
