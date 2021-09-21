<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../../');

use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__, 2));
$dotenv->load();

$container = require 'config/container.php';

$em          = $container->get(EntityManagerInterface::class);
$userService = $container->get(UserServiceInterface::class);

try {
    $userService->clearAccount();
    sleep(1);
} catch (\Throwable $th) {

}
