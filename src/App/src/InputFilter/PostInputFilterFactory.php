<?php

declare(strict_types=1);

namespace App\InputFilter;

use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;

final class PostInputFilterFactory
{
    public function __invoke(ContainerInterface $container): PostInputFilter
    {
        return new PostInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
