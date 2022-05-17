<?php

declare(strict_types=1);

namespace App\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CampaignMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): CampaignMiddleware
    {
        return new CampaignMiddleware(
            $container->get(EntityManagerInterface::class)
        );
    }
}
