<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

interface UserPreferenceInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public function setUser(User $user): void;

    public function getUser(): User;

    public function setBirthyear(?int $birthday = null): void;

    public function getBirthyear(): ?int;

    public function setLiveInCity(bool $liveInCity): void;

    public function getLiveInCity(): bool;

    public function setPostalCode(?string $postalCode = null): void;

    public function getPostalCode(): ?string;

    public function setNickname(string $nickname): void;

    public function getNickname(): string;

    public function setPrivacy(bool $privacy): void;

    public function getPrivacy(): bool;

    public function setHearAbout(string $hearAbout): void;

    public function getHearAbout(): string;

    public function setCreated(?DateTime $created = null): void;

    public function getCreated(): ?DateTime;
}
