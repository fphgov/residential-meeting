<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\InputFilter\ProjectInputFilter;
use App\Service\ProjectServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ProjectInputFilter::class);

        return new AddHandler(
            $inputFilter,
            $container->get(ProjectServiceInterface::class)
        );
    }
}
