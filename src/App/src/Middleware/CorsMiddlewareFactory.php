<?php

declare(strict_types=1);

namespace App\Middleware;

use Tuupola\Middleware\CorsMiddleware;

class CorsMiddlewareFactory
{
    public function __invoke($container)
    {
        $corsConfig = $container->get('config')['cors'] ?? [];

        return new CorsMiddleware($corsConfig);
    }
}
