<?php

declare(strict_types=1);

namespace App\Handler\Phase;

use App\Service\PhaseServiceInterface;
use Psr\Container\ContainerInterface;

final class CheckHandlerFactory
{
    public function __invoke(ContainerInterface $container): CheckHandler
    {
        return new CheckHandler(
            $container->get(PhaseServiceInterface::class)
        );
    }
}
