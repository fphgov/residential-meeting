<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Mail\MailAdapterInterface;

interface MailQueueInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): void;

    public function setMailAdapter(MailAdapterInterface $mailAdapter): void;

    public function getMailAdapter(): MailAdapterInterface;
}
