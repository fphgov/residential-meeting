<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Service\ArticleServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AdminDeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminDeleteHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);

        return new AdminDeleteHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(ArticleServiceInterface::class),
        );
    }
}
