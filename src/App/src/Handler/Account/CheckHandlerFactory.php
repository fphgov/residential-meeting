<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\InputFilter\AccountCheckFilter;
use App\Service\AccountServiceInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class CheckHandlerFactory
{
    public function __invoke(ContainerInterface $container): CheckHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(AccountCheckFilter::class);

        return new CheckHandler(
            $container->get(AccountServiceInterface::class),
            $inputFilter
        );
    }
}
