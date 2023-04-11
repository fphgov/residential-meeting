<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class QuestionServiceFactory
{
    /**
     * @return QuestionService
     */
    public function __invoke(ContainerInterface $container)
    {
        return new QuestionService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
