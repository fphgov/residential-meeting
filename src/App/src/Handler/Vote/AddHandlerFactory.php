<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\InputFilter\OfflineVoteFilter;
use App\Service\VoteServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

final class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddHandler
    {
        /** @var InputFilterPluginManager $pluginManager */
        $em            = $container->get(EntityManagerInterface::class);
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(OfflineVoteFilter::class);

        return new AddHandler(
            $em,
            $inputFilter,
            $container->get(VoteServiceInterface::class)
        );
    }
}
