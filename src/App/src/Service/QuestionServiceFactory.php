<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class QuestionServiceFactory
{
    public function __invoke(ContainerInterface $container): QuestionService
    {
        return new QuestionService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
