<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Project;
use App\Entity\CampaignTheme;
use App\Entity\Vote;
use App\Entity\OfflineVote;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_filter;
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
        foreach ($onlineResult as $onlineProjectDTO) {
            $id = $onlineProjectDTO->getId();
            $tmpDTO = clone $onlineProjectDTO;

            $offlineProjectDTO = [];
            $offlineProjectDTO = array_values(array_filter(
                $offlineResult,
                function ($e) use (&$id) {
                    return $e->getId() === $id;
                }
            ));

            if (! isset($offlineProjectDTO[0])) {
                $result[] = $tmpDTO;

                continue;
            }

            $tmpDTO->setPlusVoted($offlineProjectDTO[0]->getVoted());

            $result[] = $tmpDTO;
        }

        usort($result, fn($a, $b) => $a->getVoted() < $b->getVoted());

        return new JsonResponse([
            '_embedded' => [
                'projects' => $result,
            ]
        ]);
    }

    private function getProjectStatisticsDTO(string $className)
    {
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, COUNT(care.id), COUNT(green.id), COUNT(whole.id)) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin($className, 'care', Join::WITH, 'care.projectCare = p.id')
            ->leftJoin($className, 'green', Join::WITH, 'green.projectGreen = p.id')
            ->leftJoin($className, 'whole', Join::WITH, 'whole.projectWhole = p.id')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC');

        return $qb->getQuery()->getResult();
    }

    private function getProjectStatistics(string $className)
    {
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, COUNT(care.id), 0, 0) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin($className, 'care', Join::WITH, 'care.project = p.id')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
