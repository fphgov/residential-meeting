<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Vote;
use App\Repository\VoteRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class VoteExportModel implements ExportCsvModelInterface
{
    public const HEADER = [
        'id',
        'question_id',
        'answer',
        'zip_code',
    ];

    /** @var VoteRepositoryInterface **/
    private $voteRepository;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $this->em             = $em;
        $this->voteRepository = $this->em->getRepository(Vote::class);
    }

    public function getCsvData(): array
    {
        $votes = $this->voteRepository->findAll();

        $exportData = [];

        $exportData[] = self::HEADER;

        foreach ($votes as $vote) {
            $data = [
                $vote->getId(),
                $vote->getQuestion()->getId(),
                $vote->getAnswer(),
                $vote->getZipCode()
            ];

            $exportData[] = $data;
        }

        return $exportData;
    }
}
