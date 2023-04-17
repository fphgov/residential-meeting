<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use App\Service\MailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class AccountServiceFactory
{
    public function __invoke(ContainerInterface $container): AccountService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new AccountService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailServiceInterface::class)
        );
    }
}
