<?php

declare(strict_types=1);

namespace App\InputFilter;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;

final class UserRegistrationFilterFactory
{
    public function __invoke(ContainerInterface $container): UserRegistrationFilter
    {
        return new UserRegistrationFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
