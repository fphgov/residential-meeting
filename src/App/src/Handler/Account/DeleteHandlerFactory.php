<?php

declare(strict_types=1);

namespace App\Handler\Account;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): DeleteHandler
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new DeleteHandler(
            $config,
            $container->get(EntityManagerInterface::class)
        );
    }
}
