<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

final class MediaServiceFactory
{
    public function __invoke(ContainerInterface $container): MediaService
    {
        return new MediaService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
