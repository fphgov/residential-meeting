<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\CampaignTheme;
use App\Entity\OfflineVote;
use App\Entity\Project;
use App\Entity\Vote;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_filter;
use function array_values;
use function usort;

final class StatisticsHandler implements RequestHandlerInterface
{
    /** @var EntityManager */
    protected $em;

    public function __construct(
        EntityManager $em
    ) {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $onlineResult  = $this->getProjectStatisticsDTO(Vote::class);
        $offlineResult = $this->getProjectStatistics(OfflineVote::class);

        $result = [];
        foreach ($onlineResult as $onlineProjectDto) {
            $id     = $onlineProjectDto->getId();
            $tmpDto = clone $onlineProjectDto;

            $offlineProjectDto = [];
            $offlineProjectDto = array_values(array_filter(
                $offlineResult,
                function ($e) use (&$id) {
                    return $e->getId() === $id;
                }
            ));

            if (! isset($offlineProjectDto[0])) {
                $result[] = $tmpDto;

                continue;
            }

            $tmpDto->setPlusVoted($offlineProjectDto[0]->getVoted());

            $result[] = $tmpDto;
        }

        usort($result, fn($a, $b) => $a->getVoted() < $b->getVoted());

        return new JsonResponse([
            '_embedded' => [
                'projects' => $result,
            ],
        ]);
    }

    private function getProjectStatisticsDTO(string $className): array
    {
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, COUNT(care.id), COUNT(green.id), COUNT(whole.id), p.win) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin($className, 'care', Join::WITH, 'care.projectCare = p.id')
            ->leftJoin($className, 'green', Join::WITH, 'green.projectGreen = p.id')
            ->leftJoin($className, 'whole', Join::WITH, 'whole.projectWhole = p.id')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
            ->orderBy('p.win', 'DESC');

        return $qb->getQuery()->getResult();
    }

    private function getProjectStatistics(string $className): array
    {
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, COUNT(care.id), 0, 0, p.win) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin($className, 'care', Join::WITH, 'care.project = p.id')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
            ->orderBy('p.win', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
