<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\InputFilter\ProjectInputFilter;
use App\Service\ProjectServiceInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

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
