<?php

declare(strict_types=1);

namespace Mail\Entity;

class SimpleNotification implements EmailNotificationInterface
{
    public function __construct(
        private string $id,
        private string $email,
        private string $emailCode
    ) {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getEmailCode(): string
    {
        return $this->emailCode;
    }

    public function setEmailCode(string $emailCode): void
    {
        $this->emailCode = $emailCode;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
