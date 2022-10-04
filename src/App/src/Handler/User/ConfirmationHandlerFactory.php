<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\ProfilActivationInputFilter;
use App\Service\UserServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class ConfirmationHandlerFactory
{
    public function __invoke(ContainerInterface $container): ConfirmationHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ProfilActivationInputFilter::class);

        return new ConfirmationHandler(
            $container->get(UserServiceInterface::class),
            $inputFilter,
        );
    }
}
