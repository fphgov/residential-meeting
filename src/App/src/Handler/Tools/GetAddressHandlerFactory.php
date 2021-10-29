<?php

declare(strict_types=1);

namespace App\Handler\Tools;

use App\Service\PostalClientServiceInterface;
use Psr\Container\ContainerInterface;

final class GetAddressHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetAddressHandler
    {
        return new GetAddressHandler(
            $container->get(PostalClientServiceInterface::class)
        );
    }
}
