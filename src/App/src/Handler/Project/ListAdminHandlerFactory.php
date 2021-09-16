<?php

declare(strict_types=1);

namespace App\Handler\Project;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class ListAdminHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListAdminHandler
    {
        return new ListAdminHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
