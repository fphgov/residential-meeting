<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Service\VoteServiceInterface;
use Psr\Container\ContainerInterface;

final class ListHandlerFactory
{
    public function __invoke(ContainerInterface $container): ListHandler
    {
        return new ListHandler(
            $container->get(VoteServiceInterface::class)
        );
    }
}
