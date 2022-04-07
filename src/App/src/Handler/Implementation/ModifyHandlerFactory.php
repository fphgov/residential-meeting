<?php

declare(strict_types=1);

namespace App\Handler\Implementation;

use App\Service\ImplementationServiceInterface;
use Psr\Container\ContainerInterface;

final class ModifyHandlerFactory
{
    public function __invoke(ContainerInterface $container): ModifyHandler
    {
        return new ModifyHandler(
            $container->get(ImplementationServiceInterface::class)
        );
    }
}
