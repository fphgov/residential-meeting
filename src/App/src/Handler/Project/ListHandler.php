<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\CampaignTheme;
use App\Entity\Project;
use App\Entity\ProjectCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function intval;

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
        $query       = $queryParams['query'] ?? '';
        $tag         = $queryParams['tag'] ?? '';
        $theme       = $queryParams['theme'] ?? '';
        $location    = $queryParams['location'] ?? '';
        $page        = $queryParams['page'] ?? 1;
        $sort        = $queryParams['sort'] ?? 'ASC';
        $rand        = $queryParams['rand'] ?? '';

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW ProjectListDTO(p.id, ct.name, ct.rgb, p.title, p.description, p.location, GROUP_CONCAT(t.id), GROUP_CONCAT(t.name)) as project')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.campaignLocations', 'l')
            ->groupBy('p.id');

        if ($rand == '' && is_string($sort) && in_array(strtoupper($sort), ['ASC', 'DESC'], true)) {
            $qb->orderBy('p.title', $sort);
        } else if ($rand != '') {
            $qb->orderBy('RAND('. $rand .')');
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
            $qb->andWhere('ct.id = :themes');
            $qb->setParameter('themes', $theme);
        }

        if ($location && $location !== 0) {
            $qb->andWhere('l.id = :location');
            $qb->setParameter('location', $location);
        }

        $qb->setMaxResults(1);

        $paginator = new ProjectCollection($qb);
        $paginator->setUseOutputWalkers(false);

        $paginator->getQuery()->setFirstResult($this->pageCount * $page)->setMaxResults($this->pageCount);

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
