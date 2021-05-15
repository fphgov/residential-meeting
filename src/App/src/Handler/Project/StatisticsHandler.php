<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Project;
use App\Entity\CampaignTheme;
use App\Entity\Vote;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $pageCount  = 250;
        $repository = $this->em->getRepository(Project::class);

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectStatisticsDTO(p.id, ct.id, ct.name, ct.rgb, p.title, COUNT(care.id), COUNT(green.id), COUNT(whole.id)) as ps')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin(Vote::class, 'care', Join::WITH, 'care.projectCare = p.id')
            ->leftJoin(Vote::class, 'green', Join::WITH, 'green.projectGreen = p.id')
            ->leftJoin(Vote::class, 'whole', Join::WITH, 'whole.projectWhole = p.id')
            ->groupBy('p.id')
            ->orderBy('p.id', 'DESC');

        $result = $qb->getQuery()->getResult();

        usort($result, fn($a, $b) => $a->getVoted() < $b->getVoted());

        return new JsonResponse([
            '_embedded' => [
                'projects' => $result,
            ]
        ]);
    }
}
