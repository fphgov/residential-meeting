<?php

declare(strict_types=1);

namespace App\Entity;

interface NotificationInterface
{
    public function getId(): string;

    public function setId(string $id): void;

    public function setEmail(string $email): void;

    public function getEmail(): string;

    public function setSend(bool $send): void;

    public function getSend(): bool;
}
