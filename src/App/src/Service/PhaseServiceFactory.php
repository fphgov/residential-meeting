<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class PhaseServiceFactory
{
    /**
     * @return PhaseService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new PhaseService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
