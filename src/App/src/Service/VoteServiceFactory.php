<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\MailQueueServiceInterface;
use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Mail\Action\MailAction;

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
            $container->get(MailQueueServiceInterface::class)
        );
    }
}
