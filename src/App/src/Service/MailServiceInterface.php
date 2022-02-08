<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use App\Entity\User;

interface MailServiceInterface
{
    public function modifyMail(Mail $mail, array $filteredParams): void;

    public function send(string $mailCode, array $tplData, User $user): void;
}
