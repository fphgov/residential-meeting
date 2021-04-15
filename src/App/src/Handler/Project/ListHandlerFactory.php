<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\ProjectServiceInterface;
use Interop\Container\ContainerInterface;

final class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListHandler
    {
        return new ListHandler(
            $container->get(ProjectServiceInterface::class)
        );
    }
}
