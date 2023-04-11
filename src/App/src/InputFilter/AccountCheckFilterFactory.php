<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class AccountCheckFilterFactory
{
    public function __invoke(ContainerInterface $container): AccountCheckFilter
    {
        return new AccountCheckFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
