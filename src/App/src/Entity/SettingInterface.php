<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface SettingInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [
        'createdAt',
        'updatedAt',
    ];

    public function setClose(bool $close): void;

    public function getClose(): bool;
}
