<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\InputFilter\PostInputFilter;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AdminModifyHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminModifyHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(PostInputFilter::class);

        return new AdminModifyHandler(
            $inputFilter,
            $container->get(EntityManagerInterface::class),
            $container->get(PostServiceInterface::class),
        );
    }
}
