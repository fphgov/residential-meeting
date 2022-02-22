<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class AdminProjectInputFilterFactory
{
    public function __invoke(ContainerInterface $container): AdminProjectInputFilter
    {
        return new AdminProjectInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
