<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

final class ArticleInputFilterFactory
{
    public function __invoke(ContainerInterface $container): ArticleInputFilter
    {
        return new ArticleInputFilter(
            $container->get(AdapterInterface::class)
        );
    }
}
