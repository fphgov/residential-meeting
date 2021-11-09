<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../');

use App\Entity\Idea;
use App\Entity\WorkflowState;
use App\Entity\WorkflowStateInterface;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require 'config/container.php';

$em             = $container->get(EntityManagerInterface::class);
$ideaRepository = $em->getRepository(Idea::class);

$idea = $ideaRepository->find(750);
$idea->setWorkflowState(
    $em->getReference(WorkflowState::class, WorkflowStateInterface::STATUS_PUBLISHED)
);

$em->flush();
