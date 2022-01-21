<?php

declare(strict_types=1);

namespace App\Handler\Mail;

use App\Service\MailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class AdminModifyHandlerFactory
{
    public function __invoke(ContainerInterface $container): AdminModifyHandler
    {
        return new AdminModifyHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(MailServiceInterface::class)
        );
    }
}
