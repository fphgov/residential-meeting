<?php

declare(strict_types=1);

namespace App\Handler\Implementation;

use App\Service\ImplementationServiceInterface;
use Psr\Container\ContainerInterface;

final class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): DeleteHandler
    {
        return new DeleteHandler(
            $container->get(ImplementationServiceInterface::class)
        );
    }
}
