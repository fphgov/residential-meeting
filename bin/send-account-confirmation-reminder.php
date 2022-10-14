<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../');

use App\Entity\User;
use App\Entity\MailLog;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require 'config/container.php';

$em                = $container->get(EntityManagerInterface::class);
$userService       = $container->get(UserServiceInterface::class);
$userRepository    = $em->getRepository(User::class);
$mailLogRepository = $em->getRepository(MailLog::class);

$mailLogs = $mailLogRepository->getSendedAccountConfirmation(new DateTime('2022-10-01'));

foreach ($mailLogs as $mailLog) {
    $user = $userRepository->find($mailLog->getUser());

    if (! $user instanceof User) {
        continue;
    }

    if ($user->getActive()) {
        continue;
    }

    $hasMailLogReminder = $mailLogRepository->isSendedReminder($user, new DateTime('2022-10-01'));

    if (! $hasMailLogReminder) {
        $userService->accountConfirmationReminder($user);
    }
}
