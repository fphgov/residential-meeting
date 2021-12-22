<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class PostInputFilterFactory
{
    public function __invoke(ContainerInterface $container): PostInputFilter
    {
        return new PostInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
