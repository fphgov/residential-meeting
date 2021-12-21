<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\InputFilter\OfflineVoteFilter;
use App\Service\VoteServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

final class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container): AddHandler
    {
        $em = $container->get(EntityManagerInterface::class);

        /** @var InputFilterPluginManager $pluginManager */
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(OfflineVoteFilter::class);

        return new AddHandler(
            $em,
            $inputFilter,
            $container->get(VoteServiceInterface::class)
        );
    }
}
