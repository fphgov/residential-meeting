<?php

declare(strict_types=1);

namespace App\Handler\Plan;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class FilterHandlerFactory
{
    public function __invoke(ContainerInterface $container): FilterHandler
    {
        return new FilterHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
