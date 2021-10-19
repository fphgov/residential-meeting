<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\PhaseServiceInterface;
use App\Service\MailQueueServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Mail\Action\MailAction;
use App\Middleware\AuditMiddleware;

final class IdeaServiceFactory
{
    /**
     * @return IdeaService
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new IdeaService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(PhaseServiceInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailAction::class)->getAdapter(),
            $container->get(MailQueueServiceInterface::class)
        );
    }
}
