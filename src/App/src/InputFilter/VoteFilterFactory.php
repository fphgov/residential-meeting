<?php

declare(strict_types=1);

namespace App\InputFilter;

use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;

final class VoteFilterFactory
{
    public function __invoke(ContainerInterface $container): VoteFilter
    {
        return new VoteFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
