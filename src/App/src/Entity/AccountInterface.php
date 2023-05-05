<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntitySimpleInterface;

interface AccountInterface extends EntitySimpleInterface
{
    public function seAuthCode(string $authCode): void;

    public function geAuthCode(): string;

    public function setVoted(bool $voted): void;

    public function getVoted(): bool;

    public function getZipCode(): ?string;

    public function setZipCode(?string $zipCode): void;
}
