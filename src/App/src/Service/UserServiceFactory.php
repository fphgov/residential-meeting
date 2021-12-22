<?php

declare(strict_types=1);

namespace App\Service;

use App\Middleware\AuditMiddleware;
use App\Service\MailQueueServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Mail\Action\MailAction;
use Psr\Container\ContainerInterface;

final class UserServiceFactory
{
    /**
     * @return UserService
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new UserService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(AuditMiddleware::class)->getLogger(),
            $container->get(MailAction::class)->getAdapter(),
            $container->get(MailQueueServiceInterface::class)
        );
    }
}
