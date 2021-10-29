<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Psr\Container\ContainerInterface;

final class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListHandler
    {
        return new ListHandler(
            $container->get(UserServiceInterface::class)
        );
    }
}
