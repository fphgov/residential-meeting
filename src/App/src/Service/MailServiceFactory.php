<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class MailServiceFactory
{
    /**
     * @return MailService
     */
    public function __invoke(ContainerInterface $container): MailServiceInterface
    {
        return new MailService(
            $container->get(EntityManagerInterface::class)
        );
    }
}
