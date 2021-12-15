<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\InputFilter\PostInputFilter;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class AdminDeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminDeleteHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);

        return new AdminDeleteHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(PostServiceInterface::class),
        );
    }
}
