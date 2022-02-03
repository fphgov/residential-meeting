<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../');

use App\Entity\Idea;
use App\Service\IdeaServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require 'config/container.php';

$em             = $container->get(EntityManagerInterface::class);
$ideaService    = $container->get(IdeaServiceInterface::class);
$ideaRepository = $em->getRepository(Idea::class);

$idea = $ideaRepository->find(0);

$ideaService->sendIdeaConfirmationEmail($idea->getSubmitter(), $idea);
