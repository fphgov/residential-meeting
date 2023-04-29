<?php

declare(strict_types=1);

namespace App\Handler\Question;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class GetAllHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetAllHandler
    {
        return new GetAllHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
