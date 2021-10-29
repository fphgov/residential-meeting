<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Service\MediaServiceInterface;
use Psr\Container\ContainerInterface;

final class DownloadHandlerFactory
{
    public function __invoke(ContainerInterface $container): DownloadHandler
    {
        return new DownloadHandler(
            $container->get(MediaServiceInterface::class)
        );
    }
}
