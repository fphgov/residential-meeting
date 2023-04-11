<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Mail\MailAdapterInterface;

interface MailQueueInterface extends EntityInterface
{
    public function getNotification(): NotificationInterface;

    public function setNotification(NotificationInterface $notification): void;

    public function setMailAdapter(MailAdapterInterface $mailAdapter): void;

    public function getMailAdapter(): MailAdapterInterface;
}
