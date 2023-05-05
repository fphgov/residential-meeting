<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../../');

use App\Service\NewsletterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

if (getenv('NODE_ENV') === 'development') {
    $dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__, 2));
    $dotenv->load();
}

$container = require 'config/container.php';

$em                = $container->get(EntityManagerInterface::class);
$newsletterService = $container->get(NewsletterServiceInterface::class);

try {
    $newsletterService->process('8ILl2QAD-');
    usleep(250000); # 0.25 sec
} catch (\Throwable $th) {

}
