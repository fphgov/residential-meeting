<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\NotificationInterface;

class SimpleNotification implements NotificationInterface
{
    public function __construct(
        private string $id,
        private string $email
    ) {
        $this->id    = $id;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
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
