<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Container\ContainerInterface;

final class GetHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetHandler
    {
        return new GetHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(HalResponseFactory::class),
            $container->get(ResourceGenerator::class),
        );
    }
}
