<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../');

use App\Service\MailServiceInterface;
use Mail\Entity\SimpleNotification;
use RabbitMQ\Interfaces\RabbitMQServiceInterface;
use RabbitMQ\Consumer\WorkQueueConsumer;
use RabbitMQ\Consumer\Message;

require 'vendor/autoload.php';

if (getenv('NODE_ENV') === 'development') {
    $dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__, 1));
    $dotenv->load();
}

$container = require 'config/container.php';

$config      = $container->get('config');
$mq          = $container->get(RabbitMQServiceInterface::class);
$mailService = $container->get(MailServiceInterface::class);

$queueName = 'notification_queue';

$consumer = new WorkQueueConsumer($queueName);

$mq->setConsumer($consumer);

$mq->receive(function (Message $message) use ($mailService, $config) {
    try {
        $body = json_decode($message->getBody());

        $notification = new SimpleNotification(
            (string)$body->id,
            $body->email,
            $body->email_code
        );

        $tplData = [
            'infoMunicipality' => $config['app']['municipality'],
            'infoEmail'        => $config['app']['email'],
        ];

        $mailService->send($tplData, $notification, true);

        usleep(250000); # 0.25 sec

        $message->ack();
    } catch (\Exception $e) {
        $message->nack();
    }
});
