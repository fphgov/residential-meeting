<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Project;
use App\Entity\ProjectCollection;
use Doctrine\ORM\EntityManager;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Mezzio\Hal\ResourceGenerator\Exception\OutOfBoundsException;
use Mezzio\Router\Exception\RuntimeException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $page        = $queryParams['page'] ?? 1;

        $qb = $repository
                ->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC');

        if (intval($query) !== 0) {
            $qb->where('p.id = :id')->setParameter('id', $query);
        } else if ($query) {
            $qb
                ->where('p.title LIKE :title')->setParameter('title', "%" . $query . "%")
                ->orWhere('p.description LIKE :description')->setParameter('description', "%" . $query . "%")
                ->orWhere('p.solution LIKE :solution')->setParameter('solution', "%" . $query . "%");
        }

        $qb->setMaxResults(1);

        $collection = new ProjectCollection($qb);

        $collection->getQuery()->setFirstResult($this->pageCount * $page)->setMaxResults($this->pageCount);

        try {
            $resource = $this->resourceGenerator->fromObject($collection, $request);
        } catch (ResourceGenerator\Exception\OutOfBoundsException $e) {
            return new JsonResponse([
                'errors' => 'Bad Request',
            ], 400);
        }

        return $this->responseFactory->createResponse($request, $resource);
    }
}
