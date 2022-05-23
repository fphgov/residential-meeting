<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\VoteFilter;
use App\Service\VoteServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class VoteHandlerFactory
{
    public function __invoke(ContainerInterface $container): VoteHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(VoteFilter::class);

        return new VoteHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(VoteServiceInterface::class),
            $inputFilter
        );
    }
}
