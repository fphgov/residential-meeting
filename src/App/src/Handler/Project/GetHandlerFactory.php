<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\ProjectServiceInterface;
use Interop\Container\ContainerInterface;

final class GetHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return GetHandler
     */
    public function __invoke(ContainerInterface $container): GetHandler
    {
        return new GetHandler(
            $container->get(ProjectServiceInterface::class)
        );
    }
}
