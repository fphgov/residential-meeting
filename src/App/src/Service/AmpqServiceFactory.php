<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use Psr\Container\ContainerInterface;
use RabbitMQ\Interfaces\RabbitMQServiceInterface;

final class AmpqServiceFactory
{
    public function __invoke(ContainerInterface $container): AmpqService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new AmpqService(
            $config,
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(RabbitMQServiceInterface::class)
        );
    }
}
