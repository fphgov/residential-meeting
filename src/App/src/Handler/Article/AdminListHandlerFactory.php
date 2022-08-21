<?php

declare(strict_types=1);

namespace App\Handler\Article;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class AdminListHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminListHandler
    {
        return new AdminListHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
