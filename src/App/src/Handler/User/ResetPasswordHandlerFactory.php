<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Interop\Container\ContainerInterface;

final class ResetPasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): ResetPasswordHandler
    {
        return new ResetPasswordHandler(
            $container->get(UserServiceInterface::class)
        );
    }
}
