<?php

declare(strict_types=1);

namespace App\Handler\Account;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

final class PasswordChangeHandlerFactory
{
    public function __invoke(ContainerInterface $container): PasswordChangeHandler
    {
        return new PasswordChangeHandler(
            $container->get('config'),
            $container->get(EntityManagerInterface::class)
        );
    }
}
