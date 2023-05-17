<?php

declare(strict_types=1);

namespace App\Service;

use Mail\Entity\EmailNotificationInterface;

interface AmpqServiceInterface
{
    public function add(string $queueName, EmailNotificationInterface $notification): void;
}
