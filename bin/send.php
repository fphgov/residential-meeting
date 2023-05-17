<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../');

use RabbitMQ\Interfaces\RabbitMQServiceInterface;
use RabbitMQ\Publisher\WorkQueuePublisher;
use RabbitMQ\Job\Job;

require 'vendor/autoload.php';

if (getenv('NODE_ENV') === 'development') {
    $dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__, 1));
    $dotenv->load();
}

$container = require 'config/container.php';

$mq = $container->get(RabbitMQServiceInterface::class);

$publisher = new WorkQueuePublisher('notification_queue');

$mq->setPublisher($publisher);

for ($i=0; $i < 10; $i++) {
    $job = new Job([
        'id'         => $i,
        'email'      => 'dev@budapest.hu',
        'email_code' => 'vote-success'
    ]);

    $mq->push($job);
}

