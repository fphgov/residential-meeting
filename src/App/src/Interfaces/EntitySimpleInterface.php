<?php

declare(strict_types=1);

namespace App\Interfaces;

use DateTime;

interface EntitySimpleInterface
{
    public function getId(): int;

    public function setId(int $id): void;

    public function normalizer(string $format = null, array $context = []);
}
