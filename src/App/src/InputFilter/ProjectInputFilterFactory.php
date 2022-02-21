<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ProjectInputFilterFactory
{
    public function __invoke(ContainerInterface $container): ProjectInputFilter
    {
        return new ProjectInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
