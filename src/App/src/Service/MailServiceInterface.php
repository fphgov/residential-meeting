<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\Mail;
use Mail\Model\EmailContentModelInterface;

interface MailServiceInterface
{
    public function modifyMail(Mail $mail, array $filteredParams): void;

    public function send(string $mailCode, array $tplData, Account $account): void;

    public function sendRaw(EmailContentModelInterface $emailContentModel, array $tplData, Account $account): void;
}
