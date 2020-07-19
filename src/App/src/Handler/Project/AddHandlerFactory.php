<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\ProjectServiceInterface;
use App\Service\UserServiceInterface;
use Interop\Container\ContainerInterface;

final class AddHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AddHandler
     */
    public function __invoke(ContainerInterface $container): AddHandler
    {
        return new AddHandler(
            $container->get(UserServiceInterface::class),
            $container->get(ProjectServiceInterface::class)
        );
    }
}
