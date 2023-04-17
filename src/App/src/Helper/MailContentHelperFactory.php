<?php

declare(strict_types=1);

namespace App\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class MailContentHelperFactory
{
    public function __invoke(ContainerInterface $container): MailContentHelper
    {
        return new MailContentHelper(
            $container->get(EntityManagerInterface::class),
        );
    }
}
