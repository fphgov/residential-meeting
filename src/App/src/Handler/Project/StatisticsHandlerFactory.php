<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\PhaseServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class StatisticsHandlerFactory
{
    public function __invoke(ContainerInterface $container): StatisticsHandler
    {
        return new StatisticsHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(PhaseServiceInterface::class)
        );
    }
}
