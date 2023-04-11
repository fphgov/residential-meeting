<?php

declare(strict_types=1);

namespace App\Entity;

interface NewsletterInterface
{
    public function getId(): string;

    public function setId(string $id): void;

    public function setEmail(string $email): void;

    public function getEmail(): string;
}
