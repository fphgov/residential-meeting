<?php

declare(strict_types=1);

namespace App\Service;

use Laminas\Log\Logger;
use Mail\Entity\EmailNotificationInterface;
use RabbitMQ\Interfaces\RabbitMQServiceInterface;
use RabbitMQ\Publisher\WorkQueuePublisher;
use RabbitMQ\Job\Job;

final class AmpqService implements AmpqServiceInterface
{
    public function __construct(
        private array $config,
        private Logger $audit,
        private RabbitMQServiceInterface $mq
    ) {
        $this->config = $config;
        $this->audit  = $audit;
        $this->mq     = $mq;
    }

    public function add(string $queueName, EmailNotificationInterface $notification): void
    {
        $publisher = new WorkQueuePublisher($queueName);

        $job = new Job([
            'id'         => $notification->getId(),
            'email'      => $notification->getEmail(),
            'email_code' => $notification->getEmailCode(),
        ]);

        $this->mq->setPublisher($publisher);
        $this->mq->push($job);
    }
}
