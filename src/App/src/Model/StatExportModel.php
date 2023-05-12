<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Stat;
use App\Repository\StatRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class StatExportModel implements ExportCsvModelInterface
{
    public const HEADER = [
        'date',
        'day',
        'count',
    ];

    /** @var StatRepositoryInterface **/
    private $statRepository;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $this->em             = $em;
        $this->statRepository = $this->em->getRepository(Stat::class);
    }

    public function getCsvData(): array
    {
        $stats = $this->statRepository->findAll();

        $exportData = [];

        $exportData[] = self::HEADER;

        foreach ($stats as $stat) {
            $data = [
                $stat->getDate()->format('Y-m-d'),
                $stat->getDay(),
                $stat->getCount(),
            ];

            $exportData[] = $data;
        }

        return $exportData;
    }
}
