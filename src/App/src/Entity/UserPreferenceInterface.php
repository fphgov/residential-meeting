<?php

declare(strict_types=1);

namespace App\Entity;

interface UserPreferenceInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public function setUser(User $user): void;

    public function getUser(): User;

    public function setAddress(string $address): void;

    public function getAddress(): string;

    public function setBirthyear(?int $birthday = null): void;

    public function getBirthyear(): ?int;

    public function setLiveInCity(bool $liveInCity): void;

    public function getLiveInCity(): bool;

    public function setPostalCode(?string $postalCode = null): void;

    public function getPostalCode(): ?string;

    public function setNickname(string $nickname): void;

    public function getNickname(): string;

    public function setpolicy(bool $policy): void;

    public function getpolicy(): bool;
}
