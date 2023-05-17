<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use App\Service\MailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class ForgotAccountServiceFactory
{
    public function __invoke(ContainerInterface $container): ForgotAccountService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new ForgotAccountService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailServiceInterface::class)
        );
    }
}
