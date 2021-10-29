<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;

final class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListHandler
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new ListHandler(
            $container->get(EntityManagerInterface::class),
            isset($config['app']['pagination']['maxPageSize']) ? (int) $config['app']['pagination']['maxPageSize'] : 25,
            $container->get(HalResponseFactory::class),
            $container->get(ResourceGenerator::class),
        );
    }
}
