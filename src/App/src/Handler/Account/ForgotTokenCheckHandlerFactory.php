<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\InputFilter\ForgotTokenCheckFilter;
use App\Service\ForgotAccountServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class ForgotTokenCheckHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotTokenCheckHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ForgotTokenCheckFilter::class);

        return new ForgotTokenCheckHandler(
            $container->get(ForgotAccountServiceInterface::class),
            $inputFilter
        );
    }
}
