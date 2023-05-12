<?php

declare(strict_types=1);

namespace App\Handler\Stat;

use App\Model\StatExportModel;
use Psr\Container\ContainerInterface;

final class GetHistoryHandlerFactory
{
    public function __invoke(ContainerInterface $container): GetHistoryHandler
    {
        return new GetHistoryHandler(
            $container->get(StatExportModel::class)
        );
    }
}
