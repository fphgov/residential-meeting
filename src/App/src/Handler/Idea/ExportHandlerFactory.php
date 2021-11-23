<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Model\IdeaExportModel;
use Interop\Container\ContainerInterface;

final class ExportHandlerFactory
{
    public function __invoke(ContainerInterface $container): ExportHandler
    {
        return new ExportHandler(
            $container->get(IdeaExportModel::class)
        );
    }
}
