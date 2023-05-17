<?php

declare(strict_types=1);

namespace Mail\Entity;

interface EmailNotificationInterface
{
    public function getId(): string;

    public function setId(string $id): void;

    public function getEmailCode(): string;

    public function setEmailCode(string $emailCode): void;

    public function setEmail(string $email): void;

    public function getEmail(): string;
}
