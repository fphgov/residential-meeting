<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;

chdir(__DIR__ . '/../');

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require __DIR__ . '/container.php';

return $container->get(EntityManagerInterface::class);
