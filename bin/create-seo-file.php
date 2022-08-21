<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

chdir(__DIR__ . '/../');

use App\Entity;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require 'config/container.php';

$ogMetas = [];

$em = $container->get(EntityManagerInterface::class);

$ideaRepository    = $em->getRepository(Entity\Idea::class);
$articleRepository = $em->getRepository(Entity\Article::class);
$projectRepository = $em->getRepository(Entity\Project::class);

// Ideas
$ideas = $ideaRepository->findAll();

foreach ($ideas as $idea) {
    $ogMetas['/otletek/' . $idea->getId()] = [
        'title'       => $idea->getTitle(),
        'description' => $idea->getDescription(),
    ];
}

// Projects
$projects = $projectRepository->findAll();

foreach ($projects as $idea) {
    $ogMetas['/projektek/' . $idea->getId()] = [
        'title'       => $idea->getTitle(),
        'description' => $idea->getDescription(),
    ];
}

// Posts
$articles = $articleRepository->findAll();

foreach ($articles as $article) {
    $ogMetas['/hirek/' . $article->getSlug()] = [
        'title'       => $article->getTitle(),
        'description' => $article->getDescription(),
    ];
}

$ogMetas['/szavazas'] = [
    'title'       => 'Közösségi költségvetés 2021/2022',
    'description' => 'Szavazz 2022 legjobb ötleteire!'
];

$file = dirname(__FILE__, 2) . '/public/seo.json';
touch($file, time());
file_put_contents($file, json_encode($ogMetas));
