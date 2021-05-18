<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use App\Middleware\AuditMiddleware;
use Interop\Container\ContainerInterface;

final class ForgotPasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotPasswordHandler
    {
        return new ForgotPasswordHandler(
            $container->get(UserServiceInterface::class),
            $container->get(AuditMiddleware::class)->getLogger()
        );
    }
}
