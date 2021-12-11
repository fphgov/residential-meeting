<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\InputFilter\PostInputFilter;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class AdminAddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminAddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(PostInputFilter::class);

        return new AdminAddHandler(
            $inputFilter,
            $container->get(EntityManagerInterface::class),
            $container->get(PostServiceInterface::class),
        );
    }
}
