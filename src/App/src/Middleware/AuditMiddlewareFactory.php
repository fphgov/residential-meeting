<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\Log\Logger;

class AuditMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AuditMiddleware
    {
        $config = $container->has('config') ? $container->get('config') : [];

        if (! isset($config['logger'])) {
            throw new ServiceNotCreatedException('Missing logger configuration');
        }

        $conf = $config['logger']['AuditLogger'];

        $logger = new Logger($conf);

        return new AuditMiddleware(
            $logger
        );
    }
}
