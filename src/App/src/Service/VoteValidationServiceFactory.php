<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class VoteValidationServiceFactory
{
    /**
     * @return VoteValidationService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new VoteValidationService(
            $container->get(EntityManagerInterface::class),
            $container->get(PhaseServiceInterface::class),
            $container->get(MailServiceInterface::class)
        );
    }
}
