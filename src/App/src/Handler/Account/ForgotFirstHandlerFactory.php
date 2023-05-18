<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\InputFilter\ForgotAccountFirstCheckFilter;
use App\Service\ForgotAccountServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class ForgotFirstHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotFirstHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ForgotAccountFirstCheckFilter::class);

        return new ForgotFirstHandler(
            $container->get(ForgotAccountServiceInterface::class),
            $inputFilter
        );
    }
}
