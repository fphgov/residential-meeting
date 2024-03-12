<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../../');

use App\Entity\Notification;
use App\Service\MailServiceInterface;
use App\Model\SimpleNotification;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

if (getenv('NODE_ENV') === 'development') {
    $dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__, 2));
    $dotenv->load();
}

$container = require 'config/container.php';

$em          = $container->get(EntityManagerInterface::class);
$mailService = $container->get(MailServiceInterface::class);

$notificationRepository = $em->getRepository(Notification::class);

try {
    $notificationClients = $notificationRepository->findBy([
        'send' => false,
    ]);

    foreach ($notificationClients as $client) {
        $notification = new SimpleNotification(
            $client->getId(),
            $client->getEmail()
        );

        echo $client->getId() . "\n";

        $mailService->send('result-notification', [], $notification);

        $client->setSend(true);

        $em->flush();

        usleep(250000); # 0.25 sec
    }
} catch (\Throwable $th) {
    error_log($th->getMessage());
}