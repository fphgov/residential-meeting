<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use App\Service\MailQueueServiceInterface;
use App\Service\PhaseServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Mail\Action\MailAction;
use Psr\Container\ContainerInterface;

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
