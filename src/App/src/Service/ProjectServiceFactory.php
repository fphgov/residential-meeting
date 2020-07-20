<?php

declare(strict_types=1);

namespace App\Service;

use App\InputFilter\ProjectInputFilter;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class ProjectServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProjectService
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ProjectInputFilter::class);
        
        return new ProjectService(
            $inputFilter,
            $container->get(EntityManagerInterface::class)
        );
    }
}
