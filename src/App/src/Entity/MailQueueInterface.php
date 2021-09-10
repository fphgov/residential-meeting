<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Mail\MailAdapter;

interface MailQueueInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setMailAdapter(MailAdapter $mailAdapter): void;

    public function getMailAdapter(): MailAdapter;
}
