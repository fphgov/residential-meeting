<?php

declare(strict_types=1);

namespace Mail\Action;

use Laminas\Mail\Transport\TransportInterface;
use Psr\Container\ContainerInterface;

class MailActionFactory
{
    public function __invoke(ContainerInterface $container): MailAction
    {
        $config = $container->has('config') ? $container->get('config') : [];

        return new MailAction(
            $container->get(TransportInterface::class),
            $config['mail']
        );
    }
}
