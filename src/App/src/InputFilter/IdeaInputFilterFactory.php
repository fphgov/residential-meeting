<?php

declare(strict_types=1);

namespace App\InputFilter;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;

final class IdeaInputFilterFactory
{
    public function __invoke(ContainerInterface $container): IdeaInputFilter
    {
        return new IdeaInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
