<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountInterface;
use Mail\MailAdapterInterface;

interface MailQueueServiceInterface
{
    public function add(AccountInterface $account, MailAdapterInterface $mailAdapter): void;

    public function process(): void;
}
