<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Service\MediaServiceInterface;
use Psr\Container\ContainerInterface;

final class GetHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetHandler
    {
        return new GetHandler(
            $container->get(MediaServiceInterface::class)
        );
    }
}
