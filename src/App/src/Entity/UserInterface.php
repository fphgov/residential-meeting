<?php

declare(strict_types=1);

namespace App\Entity;

interface UserInterface {
    const DISABLE_SHOW_DEFAULT = [
        'password',
        'createdAt',
        'updatedAt',
    ];

    public function setFirstname(string $firstname);

    public function getFirstname(): string;

    public function setLastname(string $lastname);

    public function getLastname(): string;

    public function setEmail(string $email);

    public function getEmail(): string;

    public function setPassword(string $password);

    public function getPassword(): string;

    public function setRole(string $role);

    public function getRole(): ?string;

    public function generateToken(): string;
}
