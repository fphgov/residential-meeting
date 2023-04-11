<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\AccountServiceInterface;
use Psr\Container\ContainerInterface;

class AccountMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AccountMiddleware
    {
        return new AccountMiddleware(
            $container->get(AccountServiceInterface::class)
        );
    }
}
