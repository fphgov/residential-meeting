<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use App\Entity\NotificationInterface;
use Mail\Model\EmailContentModelInterface;

interface MailServiceInterface
{
    public function modifyMail(Mail $mail, array $filteredParams): void;

    public function send(string $mailCode, array $tplData, NotificationInterface $notification): void;

    public function sendRaw(EmailContentModelInterface $emailContentModel, array $tplData, NotificationInterface $notification): void;
}
