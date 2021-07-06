<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\UserRegistrationFilter;
use App\Service\UserServiceInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class RegistrationHandlerFactory
{
    public function __invoke(ContainerInterface $container): RegistrationHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(UserRegistrationFilter::class);

        return new RegistrationHandler(
            $container->get(UserServiceInterface::class),
            $inputFilter
        );
    }
}
