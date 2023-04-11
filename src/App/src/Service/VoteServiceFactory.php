<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\MailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class VoteServiceFactory
{
    public function __invoke(ContainerInterface $container): VoteService
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new VoteService(
            $config,
            $container->get(EntityManagerInterface::class),
            $container->get(MailServiceInterface::class),
        );
    }
}
