<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface SettingInterface extends EntityInterface
{
    public function setClose(bool $close): void;

    public function getClose(): bool;
}
