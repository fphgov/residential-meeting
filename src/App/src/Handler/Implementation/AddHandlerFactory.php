<?php

declare(strict_types=1);

namespace App\Handler\Implementation;

use App\Service\ImplementationServiceInterface;
use App\InputFilter\ImplementationFilter;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ImplementationFilter::class);

        return new AddHandler(
            $container->get(ImplementationServiceInterface::class),
            $inputFilter
        );
    }
}
