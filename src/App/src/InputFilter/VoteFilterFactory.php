<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class VoteFilterFactory
{
    public function __invoke(ContainerInterface $container): VoteFilter
    {
        return new VoteFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
