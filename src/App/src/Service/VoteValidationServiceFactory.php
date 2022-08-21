<?php

declare(strict_types=1);

namespace App\Service;

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
        );
    }
}
