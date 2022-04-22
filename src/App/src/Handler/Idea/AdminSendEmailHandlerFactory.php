<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Middleware\AuditMiddleware;
use App\Service\IdeaServiceInterface;
use Psr\Container\ContainerInterface;

final class AdminSendEmailHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminSendEmailHandler
    {
        return new AdminSendEmailHandler(
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(IdeaServiceInterface::class)
        );
    }
}
