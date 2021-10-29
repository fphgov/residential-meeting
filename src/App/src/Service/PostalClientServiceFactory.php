<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class PostalClientServiceFactory
{
    /**
     * @return PostalClientService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PostalClientService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
