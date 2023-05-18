<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ForgotTokenCheckFilterFactory
{
    public function __invoke(ContainerInterface $container): ForgotTokenCheckFilter
    {
        return new ForgotTokenCheckFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
