<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Idea;
use App\Service\IdeaServiceInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use Psr\Http\Message\StreamInterface;

final class IdeaAnswerImportModel implements ImportModelInterface
{
    private array $ideaAnswerData = [];

    public function import(StreamInterface $stream): void
    {
        $filename = $stream->getMetaData('uri');

        $reader = IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($filename);

        $this->ideaAnswerData = $spreadsheet->getActiveSheet()->rangeToArray(
            'A1:D' . $spreadsheet->getActiveSheet()->getHighestRow(),
            null,
            true,
            true,
            true
        );
    }

    public function getData(): array
    {
        return $this->ideaAnswerData;
    }
}
