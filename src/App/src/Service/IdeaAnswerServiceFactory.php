<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class IdeaAnswerServiceFactory
{
    /**
     * @return IdeaAnswerService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new IdeaAnswerService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
