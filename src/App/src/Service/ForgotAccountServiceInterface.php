<?php

declare(strict_types=1);

namespace App\Service;

interface ForgotAccountServiceInterface
{
    public function checkAvailable(string $districtName): bool;

    public function generateToken(string $email): void;
}
