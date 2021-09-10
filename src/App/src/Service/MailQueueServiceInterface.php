<?php

declare(strict_types=1);

namespace App\Service;

use Mail\MailAdapter;

interface MailQueueServiceInterface
{
    public function add(MailAdapter $mailAdapter): void;

    public function process(): void;
}
