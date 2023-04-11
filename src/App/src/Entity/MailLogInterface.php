<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface MailLogInterface extends EntityInterface
{
    public function getNotification(): NotificationInterface;

    public function setNotification(NotificationInterface $user): void;

    public function setMessageId(string $messageId): void;

    public function getMessageId(): string;

    public function setName(string $type): void;

    public function getName(): string;
}
