<?php

declare(strict_types=1);

namespace App\Handler\Question;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class GetNavigationHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetNavigationHandler
    {
        return new GetNavigationHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
