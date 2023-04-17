<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class SettingServiceFactory
{
    public function __invoke(ContainerInterface $container): SettingService
    {
        return new SettingService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
