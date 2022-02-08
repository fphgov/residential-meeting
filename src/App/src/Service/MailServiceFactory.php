<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\MailContentHelper;
use App\Service\MailQueueServiceInterface;
use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Mail\Action\MailAction;

final class MailServiceFactory
{
    /**
     * @return MailService
     */
    public function __invoke(ContainerInterface $container): MailServiceInterface
    {
        return new MailService(
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailAction::class)->getAdapter(),
            $container->get(MailContentHelper::class),
            $container->get(MailQueueServiceInterface::class)
        );
    }
}
