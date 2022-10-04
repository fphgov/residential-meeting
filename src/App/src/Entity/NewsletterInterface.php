<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntityInterface;

interface NewsletterInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setFirstname(string $firstname): void;

    public function getFirstname(): string;

    public function setLastname(string $lastname): void;

    public function getLastname(): string;

    public function setEmail(string $email): void;

    public function getEmail(): string;

    public function setSync(bool $sync): void;

    public function getSync(): bool;
}
