<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\Idea;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FilterHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // $entityRepository = $this->entityManager->getRepository(Idea::class);

        return new JsonResponse([
            'theme'    => [
                ['code' => 'GREEN', 'name' => 'Zöld Budapest'],
                ['code' => 'CARE', 'name' => 'Esélyteremtő Budapest'],
                ['code' => 'WHOLE', 'name' => 'Nyitott Budapest'],
            ],
            'location' => [
                ['code' => 'AREA0', 'name' => 'Nem köthető konkrét helyszínhez'],
                ['code' => 'AREA1', 'name' => 'I. kerület'],
                ['code' => 'AREA2', 'name' => 'II. kerület'],
                ['code' => 'AREA3', 'name' => 'III. kerület'],
                ['code' => 'AREA4', 'name' => 'IV. kerület'],
                ['code' => 'AREA5', 'name' => 'V. kerület'],
                ['code' => 'AREA6', 'name' => 'VI. kerület'],
                ['code' => 'AREA7', 'name' => 'VII. kerület'],
                ['code' => 'AREA8', 'name' => 'VIII. kerület'],
                ['code' => 'AREA9', 'name' => 'IX. kerület'],
                ['code' => 'AREA10', 'name' => 'X. kerület'],
                ['code' => 'AREA11', 'name' => 'XI. kerület'],
                ['code' => 'AREA12', 'name' => 'XII. kerület'],
                ['code' => 'AREA13', 'name' => 'XIII. kerület'],
                ['code' => 'AREA14', 'name' => 'XIV. kerület'],
                ['code' => 'AREA15', 'name' => 'XV. kerület'],
                ['code' => 'AREA16', 'name' => 'XVI. kerület'],
                ['code' => 'AREA17', 'name' => 'XVII. kerület'],
                ['code' => 'AREA18', 'name' => 'XVIII. kerület'],
                ['code' => 'AREA19', 'name' => 'XIX. kerület'],
                ['code' => 'AREA20', 'name' => 'XX. kerület'],
                ['code' => 'AREA21', 'name' => 'XXI. kerület'],
                ['code' => 'AREA22', 'name' => 'XXII. kerület'],
                ['code' => 'AREA23', 'name' => 'Margitsziget'],
            ],
            'campaign' => [
                ['id' => 2, 'name' => '2021/2022'],
                ['id' => 1, 'name' => '2020/2021'],
            ],
            'status'   => [
                ['code' => 'published', 'name' => 'Beérkezett, feldolgozásra vár'],
                ['code' => 'pre_council', 'name' => 'Szakmailag jóváhagyva, tanács elé kerül'],
                ['code' => 'voting_list', 'name' => 'Szavazólapra került'],
                ['code' => 'under_construction', 'name' => 'Szavazáson nyert, megvalósítás alatt áll'],
                ['code' => 'ready', 'name' => 'Megvalósult'],
                ['code' => 'not_voted', 'name' => 'Szavazólapra került, de szavazáson nem nyert'],
                ['code' => 'council_rejected', 'name' => 'Szakmai jóváhagyást nyert, tanács nem fogadta be'],
                ['code' => 'status_rejected', 'name' => 'Nem kapott szakmai jóváhagyást'],
            ],
        ]);
    }
}
