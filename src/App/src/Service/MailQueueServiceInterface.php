<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NotificationInterface;
use Mail\MailAdapterInterface;

interface MailQueueServiceInterface
{
    public function add(NotificationInterface $notification, MailAdapterInterface $mailAdapter): void;

    public function process(): void;
}
