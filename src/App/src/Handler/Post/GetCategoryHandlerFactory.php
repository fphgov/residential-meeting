<?php

declare(strict_types=1);

namespace App\Handler\Post;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class GetCategoryHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetCategoryHandler
    {
        return new GetCategoryHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
