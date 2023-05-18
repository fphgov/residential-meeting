<?php

declare(strict_types=1);

namespace App\Service;

use Laminas\Diactoros\UploadedFile;

interface ForgotAccountServiceInterface
{
    public function checkAvailable(string $districtName): bool;

    public function generateToken(string $email): void;

    public function storeAccountRequest(string $token, UploadedFile $file): void;
}
