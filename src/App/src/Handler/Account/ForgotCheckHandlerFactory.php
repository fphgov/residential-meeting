<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\InputFilter\ForgotDistrictCheckFilter;
use App\Service\ForgotAccountServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class ForgotCheckHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotCheckHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ForgotDistrictCheckFilter::class);

        return new ForgotCheckHandler(
            $container->get(ForgotAccountServiceInterface::class),
            $inputFilter
        );
    }
}
