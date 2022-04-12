<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Middleware\AuditMiddleware;
use App\Service\IdeaAnswerServiceInterface;
use Interop\Container\ContainerInterface;

final class AdminImportAnswerHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminImportAnswerHandler
    {
        return new AdminImportAnswerHandler(
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(IdeaAnswerServiceInterface::class)
        );
    }
}
