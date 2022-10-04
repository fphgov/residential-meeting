<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ProfilActivationInputFilterFactory
{
    public function __invoke(ContainerInterface $container): ProfilActivationInputFilter
    {
        return new ProfilActivationInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
