<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Psr\Container\ContainerInterface;

final class PrizeHandlerFactory
{
    public function __invoke(ContainerInterface $container): PrizeHandler
    {
        return new PrizeHandler(
            $container->get(UserServiceInterface::class)
        );
    }
}
