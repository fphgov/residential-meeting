<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\MailContentHelper;
use App\Helper\MailContentRawHelper;
use App\Middleware\AuditMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Mail\Action\MailAction;
use Psr\Container\ContainerInterface;

final class MailServiceFactory
{
    public function __invoke(ContainerInterface $container): MailServiceInterface
    {
        return new MailService(
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailAction::class)->getAdapter(),
            $container->get(MailContentHelper::class),
            $container->get(MailContentRawHelper::class)
        );
    }
}
