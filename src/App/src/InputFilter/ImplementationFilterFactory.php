<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ImplementationFilterFactory
{
    public function __invoke(ContainerInterface $container): ImplementationFilter
    {
        return new ImplementationFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
