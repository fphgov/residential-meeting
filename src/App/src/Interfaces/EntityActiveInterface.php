<?php

declare(strict_types=1);

namespace App\Interfaces;

interface EntityActiveInterface
{
    public function getActive(): bool;

    public function setActive(bool $active): void;
}
