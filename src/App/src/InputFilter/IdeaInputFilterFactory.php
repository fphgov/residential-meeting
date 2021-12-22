<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class IdeaInputFilterFactory
{
    public function __invoke(ContainerInterface $container): IdeaInputFilter
    {
        return new IdeaInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
