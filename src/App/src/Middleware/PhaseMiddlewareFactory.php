<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\PhaseServiceInterface;
use Psr\Container\ContainerInterface;

class PhaseMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): PhaseMiddleware
    {
        return new PhaseMiddleware(
            $container->get(PhaseServiceInterface::class)
        );
    }
}
