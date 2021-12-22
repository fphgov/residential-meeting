<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class UserRegistrationFilterFactory
{
    public function __invoke(ContainerInterface $container): UserRegistrationFilter
    {
        return new UserRegistrationFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
