<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface AccountInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function seAuthCode(string $authCode): void;

    public function geAuthCode(): string;

    public function setEmail(?string $email = null): void;

    public function getEmail(): ?string;

    public function setVoted(bool $voted): void;

    public function getVoted(): bool;

    public function setPrivacy(bool $privacy): void;

    public function getPrivacy(): bool;

    public function setNewsletter(bool $newsletter): void;

    public function getNewsletter(): bool;

    public function generateToken(): string;
}
