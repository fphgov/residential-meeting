<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class ImplementationServiceFactory
{
    /**
     * @return ImplementationService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ImplementationService(
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
        );
    }
}
