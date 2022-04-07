<?php

declare(strict_types=1);

namespace App\Handler\Implementation;

use App\Service\ImplementationServiceInterface;
use Psr\Container\ContainerInterface;

final class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListHandler
    {
        return new ListHandler(
            $container->get(ImplementationServiceInterface::class)
        );
    }
}
