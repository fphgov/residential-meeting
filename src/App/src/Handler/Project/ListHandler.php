<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Campaign;
use App\Entity\CampaignTheme;
use App\Entity\Project;
use App\Entity\ProjectCollection;
use App\Entity\WorkflowState;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function explode;
use function in_array;
use function intval;
use function is_string;
use function str_replace;
use function strtoupper;

final class ListHandler implements RequestHandlerInterface
{
    /** @var EntityManager */
    protected $em;

    /** @var int */
    protected $pageCount;

    /** @var HalResponseFactory */
    protected $responseFactory;

    /** @var ResourceGenerator */
    protected $resourceGenerator;

    public function __construct(
        EntityManager $em,
        int $pageCount,
        HalResponseFactory $responseFactory,
        ResourceGenerator $resourceGenerator
    ) {
        $this->em                = $em;
        $this->pageCount         = $pageCount;
        $this->responseFactory   = $responseFactory;
        $this->resourceGenerator = $resourceGenerator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $repository = $this->em->getRepository(Project::class);

        $queryParams = $request->getQueryParams();
        $ids         = $queryParams['ids'] ?? '';
        $query       = $queryParams['query'] ?? '';
        $tag         = $queryParams['tag'] ?? '';
        $theme       = $queryParams['theme'] ?? '';
        $location    = $queryParams['location'] ?? '';
        $campaign    = $queryParams['campaign'] ?? '';
        $page        = $queryParams['page'] ?? 1;
        $sort        = $queryParams['sort'] ?? 'ASC';
        $rand        = $queryParams['rand'] ?? '';
        $status      = $queryParams['status'] ?? '';
        $limit       = $queryParams['limit'] ?? '';

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectListDTO(p.id, c.shortTitle, ct.name, ct.rgb, p.title, p.description, p.location, w.code, w.title, GROUP_CONCAT(t.id), GROUP_CONCAT(t.name)) as project')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->join(Campaign::class, 'c', Join::WITH, 'c.id = ct.campaign')
            ->join(WorkflowState::class, 'w', Join::WITH, 'w.id = p.workflowState')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.campaignLocations', 'cl')
            ->groupBy('p.id');

        if ($rand === '' && is_string($sort) && in_array(strtoupper($sort), ['ASC', 'DESC'], true)) {
            $qb->orderBy('p.title', $sort);
        } elseif ($rand !== '') {
            $qb->orderBy('RAND(' . $rand . ')');
        } else {
            $qb->orderBy('p.title', 'ASC');
        }

        if (intval($query) !== 0) {
            $qb->where('p.id = :id')->setParameter('id', $query);
        } elseif ($query) {
            $qb
                ->where('p.title LIKE :title')->setParameter('title', "%" . $query . "%")
                ->orWhere('p.description LIKE :description')->setParameter('description', "%" . $query . "%")
                ->orWhere('p.solution LIKE :solution')->setParameter('solution', "%" . $query . "%");
        }

        if ($tag) {
            $qb->andWhere('t.id = :tags');
            $qb->setParameter('tags', $tag);
        }

        if ($theme && $theme !== 0) {
            $qb->andWhere('ct.code = :themes');
            $qb->setParameter('themes', strtoupper($theme));
        }

        if ($location && intval($location) && $location !== 0) {
            $qb->andWhere('cl.id = :location');
            $qb->setParameter('location', $location);
        }

        if ($location && is_string($location) && $location !== 0) {
            $qb->andWhere('cl.code = :location');
            $qb->setParameter('location', $location);
        }

        if ($campaign && $campaign !== 0) {
            $qb->andWhere('ct.campaign = :campaign');
            $qb->setParameter('campaign', $campaign);
        }

        if ($ids && $ids !== 0) {
            $qb->andWhere('p.id IN (:ids)');
            $qb->setParameter('ids', explode(';', str_replace(',', ';', $ids)));
        }

        if ($status && $status !== 0) {
            $qb->andWhere('w.code IN (:status)');
            $qb->setParameter('status', strtoupper($status));
        }

        $qb->andWhere('w.id IN (140, 200)');

        $qb->setMaxResults(1);

        $paginator = new ProjectCollection($qb);
        $paginator->setUseOutputWalkers(false);

        if ($limit && $limit !== 0) {
            $paginator->getQuery()->setFirstResult(0)->setMaxResults((int)$limit);
        } else {
            $paginator->getQuery()->setFirstResult($this->pageCount * $page)->setMaxResults($this->pageCount);
        }

        try {
            $resource = $this->resourceGenerator->fromObject($paginator, $request);
        } catch (ResourceGenerator\Exception\OutOfBoundsException $e) {
            return new JsonResponse([
                'errors' => 'Bad Request',
            ], 400);
        }

        return $this->responseFactory->createResponse($request, $resource);
    }
}
