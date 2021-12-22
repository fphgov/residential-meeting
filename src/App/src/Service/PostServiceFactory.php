<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class PostServiceFactory
{
    /**
     * @return PostService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PostService(
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
        );
    }
}