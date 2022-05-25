<?php

declare(strict_types=1);

namespace App\Handler\Project;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Container\ContainerInterface;

final class AdminGetHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminGetHandler
    {
        return new AdminGetHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(HalResponseFactory::class),
            $container->get(ResourceGenerator::class),
        );
    }
}