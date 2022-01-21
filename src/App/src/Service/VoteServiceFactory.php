<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\MailContentHelper;
use App\Middleware\AuditMiddleware;
use App\Service\MailQueueServiceInterface;
use App\Service\PhaseServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Mail\Action\MailAction;
use Psr\Container\ContainerInterface;

final class VoteServiceFactory
{
    /**
     * @return VoteService
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new VoteService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailAction::class)->getAdapter(),
            $container->get(MailContentHelper::class),
            $container->get(MailQueueServiceInterface::class),
            $container->get(PhaseServiceInterface::class)
        );
    }
}
