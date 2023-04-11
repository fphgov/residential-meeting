<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\InputFilter\VoteFilter;
use App\Service\VoteServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(VoteFilter::class);

        return new AddHandler(
            $container->get(VoteServiceInterface::class),
            $inputFilter
        );
    }
}
