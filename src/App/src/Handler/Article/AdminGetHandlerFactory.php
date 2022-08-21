<?php

declare(strict_types=1);

namespace App\Handler\Article;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class AdminGetHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminGetHandler
    {
        return new AdminGetHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
