<?php

declare(strict_types=1);

namespace App\Handler\Stat;

use App\Model\VoteExportModel;
use Psr\Container\ContainerInterface;

final class GetVoteHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetVoteHandler
    {
        return new GetVoteHandler(
            $container->get(VoteExportModel::class)
        );
    }
}
