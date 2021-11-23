<?php

declare(strict_types=1);

namespace App\Handler\Workflow;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class GetStatesHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetStatesHandler
    {
        return new GetStatesHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
