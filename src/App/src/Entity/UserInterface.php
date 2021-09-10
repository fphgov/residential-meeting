<?php

declare(strict_types=1);

namespace App\Entity;

interface UserInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'userPreference',
        'vote',
        'password',
        'createdAt',
        'updatedAt',
    ];

    public function setUserPreference(UserPreference $userPreference): void;

    public function getUserPreference(): UserPreference;

    public function setLuteceId(?string $luteceId = null): void;

    public function getLuteceId(): ?string;

    public function setFirstname(string $firstname): void;

    public function getFirstname(): string;

    public function setLastname(string $lastname): void;

    public function getLastname(): string;

    public function setEmail(string $email): void;

    public function getEmail(): string;

    public function setPassword(string $password): void;

    public function getPassword(): string;

    public function setRole(string $role): void;

    public function getRole(): ?string;
}
