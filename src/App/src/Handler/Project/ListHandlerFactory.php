<?php

declare(strict_types=1);

namespace App\Handler\Project;

use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\UserServiceInterface;

final class ListHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return ListHandler
     */
    public function __invoke(ContainerInterface $container) : ListHandler
    {
        return new ListHandler(
            $container->get(UserServiceInterface::class)
        );
    }
}
