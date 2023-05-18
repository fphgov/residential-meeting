<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\InputFilter\ForgotAccountSecondCheckFilter;
use App\Service\ForgotAccountServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class ForgotSecondHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotSecondHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ForgotAccountSecondCheckFilter::class);

        return new ForgotSecondHandler(
            $container->get(ForgotAccountServiceInterface::class),
            $inputFilter
        );
    }
}
