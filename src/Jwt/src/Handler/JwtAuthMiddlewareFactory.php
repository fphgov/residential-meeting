<?php

declare(strict_types=1);

namespace Jwt\Handler;

use Tuupola\Middleware\JwtAuthentication;
use Psr\Container\ContainerInterface;

class JwtAuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): JwtAuthMiddleware
    {
        $config = $container->has('config') ? $container->get('config') : [];

        if (! isset($config['jwt'])) {
            throw new Exception('Missing JWT configuration');
        }

        $auth = new JwtAuthentication([
            "secure"    => true,
            "relaxed"   => ["localhost"],
            "secret"    => $config['jwt']['auth']['secret'],
            "attribute" => JwtAuthMiddleware::class,
        ]);

        return new JwtAuthMiddleware(
            $auth
        );
    }
}
