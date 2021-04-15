<?php

declare(strict_types=1);

namespace App\Entity;

interface CampaignInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];
}
