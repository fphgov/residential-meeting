<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntitySimpleInterface;

interface SettingInterface extends EntitySimpleInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [
        'createdAt',
        'updatedAt',
    ];

    public function setKey(string $key): void;

    public function getKey(): string;

    public function setValue(string $value): void;

    public function getValue(): string;
}
