<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface MailLogInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getAccount(): AccountInterface;

    public function setAccount(AccountInterface $user): void;

    public function setMessageId(string $messageId): void;

    public function getMessageId(): string;

    public function setName(string $type): void;

    public function getName(): string;
}
