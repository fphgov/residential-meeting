<?php

declare(strict_types=1);

namespace App\Entity;

interface MailQueueInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];
}
