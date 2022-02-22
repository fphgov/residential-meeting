<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class IdeaServiceFactory
{
    /**
     * @return IdeaService
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new IdeaService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(PhaseServiceInterface::class),
            $container->get(MailServiceInterface::class)
        );
    }
}
