<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;

interface MailServiceInterface
{
    public function modifyMail(Mail $mail, array $filteredParams): void;
}
