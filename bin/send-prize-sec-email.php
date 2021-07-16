<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../');

use App\Entity\User;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require 'config/container.php';

$em             = $container->get(EntityManagerInterface::class);
$userService    = $container->get(UserServiceInterface::class);
$userRepository = $em->getRepository(User::class);

$users = $userRepository->getPrizeNotificationListSec(20);

foreach ($users as $user) {
    $userService->sendPrizeNotificationSec($user);
}
