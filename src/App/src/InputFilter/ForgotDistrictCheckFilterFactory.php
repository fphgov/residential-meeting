<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ForgotDistrictCheckFilterFactory
{
    public function __invoke(ContainerInterface $container): ForgotDistrictCheckFilter
    {
        return new ForgotDistrictCheckFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
