<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Interop\Container\ContainerInterface;

final class ActivateHandlerFactory
{
    public function __invoke(ContainerInterface $container): ActivateHandler
    {
        return new ActivateHandler(
            $container->get(UserServiceInterface::class)
        );
    }
}
