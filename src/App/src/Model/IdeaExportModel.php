<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\IdeaServiceInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function in_array;
use function implode;

final class IdeaExportModel implements ExportModelInterface
{
    public const HEADER = [
        'ID',
        'Ötlet megnevezése',
        'Link az ötlethet',
        'Mire megoldás?',
        'Leírás',
        'Helyszín megnevezése',
        'Hivatkozások',
        'Dokumentumok',
        'Kategória',
        'Becsült költség',
        'Részvétel (I/N)',
        'Részvétel milyen módon',
    ];

    public const DISABLE_AUTO_RESIZE_COLS = [
        'D',
        'E'
    ];

    private array $config;
    private Spreadsheet $spreadsheet;
    private IdeaServiceInterface $ideaService;

    public function __construct(
        array $config,
        Spreadsheet $spreadsheet,
        IdeaServiceInterface $ideaService
    ) {
        $this->config      = $config;
        $this->spreadsheet = $spreadsheet;
        $this->ideaService = $ideaService;
    }

    public function getWriter(): IWriter
    {
        $ideaRepository = $this->ideaService->getRepository();

        $ideaList = $ideaRepository->findBy([], [
            'id' => 'ASC',
        ]);

        $data = [];

        $data[] = self::HEADER;
        foreach ($ideaList as $idea) {
            $data[] = [
                $idea->getId(),
                $idea->getTitle(),
                $this->config['app']['url'] . '/otletek/' . $idea->getId(),
                $idea->getSolution(),
                $idea->getDescription(),
                $idea->getLocationDescription(),
                implode(',', $idea->getLinks()),
                $idea->getWorkflowState()->getTitle(),
                $idea->getCampaignTheme()->getName(),
                $idea->getCost(),
                $idea->getParticipate() ? 'Igen' : 'Nem',
                $idea->getParticipateComment(),
            ];
        }

        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Ötletek');
        $sheet->fromArray($data, null, 'A1');

        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'] as $col) {
            if (in_array($col, self::DISABLE_AUTO_RESIZE_COLS, true)) {
                $sheet->getColumnDimension($col)->setWidth(24);
            } else {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $this->spreadsheet->removeSheetByIndex(0);

        return new Xlsx($this->spreadsheet);
    }
}
