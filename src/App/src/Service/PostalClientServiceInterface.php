<?php

declare(strict_types=1);

namespace App\Service;

interface PostalClientServiceInterface
{
    public function getAddress(string $address): array;
}
