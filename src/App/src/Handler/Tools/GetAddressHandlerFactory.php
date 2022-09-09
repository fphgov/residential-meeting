<?php

declare(strict_types=1);

namespace App\Handler\Tools;

use Psr\Container\ContainerInterface;
use FphGov\Arcgis\Service\ArcgisServiceInterface;

final class GetAddressHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetAddressHandler
    {
        return new GetAddressHandler(
            $container->get(ArcgisServiceInterface::class)
        );
    }
}
