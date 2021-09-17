<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\UserInterface;
use Mail\MailAdapterInterface;

interface MailQueueServiceInterface
{
    public function add(UserInterface $user, MailAdapterInterface $mailAdapter): void;

    public function process(): void;
}
