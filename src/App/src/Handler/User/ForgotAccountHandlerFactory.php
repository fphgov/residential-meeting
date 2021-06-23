<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Middleware\AuditMiddleware;
use App\Service\UserServiceInterface;
use Interop\Container\ContainerInterface;

final class ForgotAccountHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotAccountHandler
    {
        return new ForgotAccountHandler(
            $container->get(UserServiceInterface::class),
            $container->get(AuditMiddleware::class)->getLogger()
        );
    }
}
