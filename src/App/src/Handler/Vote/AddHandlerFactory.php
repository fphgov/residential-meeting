<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\InputFilter\VoteFilter;
use App\Service\VoteServiceInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(VoteFilter::class);

        return new AddHandler(
            $inputFilter,
            $container->get(VoteServiceInterface::class)
        );
    }
}
