<?php

declare(strict_types=1);

namespace App\Entity;

interface IdeaInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'campaignTheme',
        'campaignLocation',
        'submitter',
        'updatedAt',
    ];
}
