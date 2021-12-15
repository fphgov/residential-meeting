<?php

declare(strict_types=1);

namespace App\Handler\Post;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class GetStatusHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetStatusHandler
    {
        return new GetStatusHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
