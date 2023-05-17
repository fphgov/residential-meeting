<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use Mail\Entity\EmailNotificationInterface;
use Mail\Model\EmailContentModelInterface;

interface MailServiceInterface
{
    public function modifyMail(Mail $mail, array $filteredParams): void;

    public function send(
        array $tplData,
        EmailNotificationInterface $notification,
        bool $useException = false
    ): void;

    public function sendRaw(
        EmailContentModelInterface $emailContentModel,
        array $tplData,
        EmailNotificationInterface $notification,
        bool $useException = false
    ): void;
}
