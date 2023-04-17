<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface AccountInterface extends EntityInterface
{
    public function seAuthCode(string $authCode): void;

    public function geAuthCode(): string;

    public function setVoted(bool $voted): void;

    public function getVoted(): bool;

    public function setPrivacy(bool $privacy): void;

    public function getPrivacy(): bool;
}
