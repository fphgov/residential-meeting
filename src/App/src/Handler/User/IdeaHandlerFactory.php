<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\IdeaInputFilter;
use App\Service\IdeaServiceInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class IdeaHandlerFactory
{
    public function __invoke(ContainerInterface $container): IdeaHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(IdeaInputFilter::class);

        return new IdeaHandler(
            $container->get(IdeaServiceInterface::class),
            $inputFilter
        );
    }
}
