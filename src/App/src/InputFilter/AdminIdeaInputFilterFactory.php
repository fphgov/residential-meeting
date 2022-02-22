<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class AdminIdeaInputFilterFactory
{
    public function __invoke(ContainerInterface $container): AdminIdeaInputFilter
    {
        return new AdminIdeaInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
