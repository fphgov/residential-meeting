<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\AmpqServiceInterface;
use App\Service\NewsletterServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use App\Middleware\AuditMiddleware;

final class VoteServiceFactory
{
    public function __invoke(ContainerInterface $container): VoteService
    {
        return new VoteService(
            $container->get(EntityManagerInterface::class),
            $container->get(AmpqServiceInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(NewsletterServiceInterface::class),
        );
    }
}
