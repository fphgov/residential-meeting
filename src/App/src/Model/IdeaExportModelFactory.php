<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\IdeaServiceInterface;
use Interop\Container\ContainerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

final class IdeaExportModelFactory
{
    /**
     * @return IdeaExportModel
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("fphgov")
            ->setLastModifiedBy('fphgov')
            ->setTitle("Export")
            ->setSubject("Export")
            ->setDescription("Export");

        return new IdeaExportModel(
            $config,
            $spreadsheet,
            $container->get(IdeaServiceInterface::class)
        );
    }
}
