<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

final class MailQueueServiceFactory
{
    public function __invoke(ContainerInterface $container): MailQueueService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new MailQueueService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
        );
    }
}
