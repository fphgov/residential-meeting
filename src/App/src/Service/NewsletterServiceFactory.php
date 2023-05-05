<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class NewsletterServiceFactory
{
    public function __invoke(ContainerInterface $container): NewsletterService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new NewsletterService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger()
        );
    }
}
