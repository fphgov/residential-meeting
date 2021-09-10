<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\VoteFilter;
use App\Service\SettingServiceInterface;
use App\Service\VoteServiceInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class VoteHandlerFactory
{
    public function __invoke(ContainerInterface $container): VoteHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(VoteFilter::class);

        return new VoteHandler(
            $container->get(VoteServiceInterface::class),
            $inputFilter,
            $container->get(SettingServiceInterface::class)
        );
    }
}
