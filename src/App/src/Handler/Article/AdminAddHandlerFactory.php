<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\InputFilter\ArticleInputFilter;
use App\Service\ArticleServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AdminAddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminAddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ArticleInputFilter::class);

        return new AdminAddHandler(
            $inputFilter,
            $container->get(EntityManagerInterface::class),
            $container->get(ArticleServiceInterface::class),
        );
    }
}
