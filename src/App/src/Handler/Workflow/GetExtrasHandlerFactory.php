<?php

declare(strict_types=1);

namespace App\Handler\Workflow;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class GetExtrasHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetExtrasHandler
    {
        return new GetExtrasHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
