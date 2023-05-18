<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ForgotAccountSecondCheckFilterFactory
{
    public function __invoke(ContainerInterface $container): ForgotAccountSecondCheckFilter
    {
        return new ForgotAccountSecondCheckFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
