<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use App\Entity\Notification;
use Mail\Model\EmailContentModelInterface;

interface MailServiceInterface
{
    public function modifyMail(Mail $mail, array $filteredParams): void;

    public function send(string $mailCode, array $tplData, Notification $notification): void;

    public function sendRaw(EmailContentModelInterface $emailContentModel, array $tplData, Notification $notification): void;
}
