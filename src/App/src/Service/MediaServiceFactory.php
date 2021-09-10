<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

final class MediaServiceFactory
{
    public function __invoke(ContainerInterface $container): MediaService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new MediaService(
            $config,
            $container->get(EntityManagerInterface::class)
        );
    }
}
