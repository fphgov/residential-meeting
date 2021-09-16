<?php

declare(strict_types=1);

namespace App\InputFilter;

use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;

final class OfflineVoteFilterFactory
{
    public function __invoke(ContainerInterface $container): OfflineVoteFilter
    {
        return new OfflineVoteFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
