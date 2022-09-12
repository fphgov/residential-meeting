<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\CampaignTheme;
use App\Entity\OfflineVote;
use App\Entity\Project;
use App\Entity\Vote;
use App\Entity\CampaignInterface;
use App\Entity\WorkflowStateInterface;
use App\Service\PhaseServiceInterface;
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

    /** @var PhaseServiceInterface */
    protected $phaseService;

    public function __construct(
        EntityManager $em,
        PhaseServiceInterface $phaseService
    ) {
        $this->em           = $em;
        $this->phaseService = $phaseService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $phase = $this->phaseService->getCurrentPhase();

        $onlineResult  = $this->getProjectStatisticsDTO($phase->getCampaign());
        $offlineResult = $this->getProjectStatistics($phase->getCampaign());

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

        usort($result, function($a, $b): int {
            if ($a->getVoted() == $b->getVoted()) {
                return 0;
            }

            return $a->getVoted() < $b->getVoted() ? 1 : -1;
        });

        return new JsonResponse([
            '_embedded' => [
                'projects' => $result,
            ],
        ]);
    }

    private function getProjectStatisticsDTO(CampaignInterface $campaign): array
    {
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, IDENTITY(p.projectType), COUNT(votes.id), p.win) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin(Vote::class, 'votes', Join::WITH, 'votes.project = p.id')
            ->where('p.campaign = :campaign')
            ->andWhere('p.workflowState = :workflowState')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
            ->orderBy('p.win', 'DESC')
            ->setParameters([
                'campaign'      => $campaign,
                'workflowState' => WorkflowStateInterface::STATUS_VOTING_LIST,
            ]);

        return $qb->getQuery()->getResult();
    }

    private function getProjectStatistics(CampaignInterface $campaign): array
    {
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, IDENTITY(p.projectType), COUNT(votes.id), p.win) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin(OfflineVote::class, 'votes', Join::WITH, 'votes.project = p.id')
            ->where('p.campaign = :campaign')
            ->andWhere('p.workflowState = :workflowState')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC')
            ->orderBy('p.win', 'DESC')
            ->setParameters([
                'campaign'      => $campaign,
                'workflowState' => WorkflowStateInterface::STATUS_VOTING_LIST,
            ]);

        return $qb->getQuery()->getResult();
    }
}
