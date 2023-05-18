<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use DateTime;

interface ForgotAccountInterface
{
    public function getToken(): UuidInterface;

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getExpirationDate(): DateTime;

    public function setExpirationDate(DateTime $expirationDate): void;
}
