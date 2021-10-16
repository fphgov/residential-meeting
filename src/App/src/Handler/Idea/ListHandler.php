<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\CampaignTheme;
use App\Entity\Idea;
use App\Entity\IdeaCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;
use function intval;
use function is_string;
use function strtoupper;
use function str_replace;
use function explode;

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
        $repository = $this->em->getRepository(Idea::class);

        $queryParams = $request->getQueryParams();
        $ids         = $queryParams['ids'] ?? '';
        $query       = $queryParams['query'] ?? '';
        $theme       = $queryParams['theme'] ?? '';
        $location    = $queryParams['location'] ?? '';
        $campaign    = $queryParams['campaign'] ?? '';
        $page        = $queryParams['page'] ?? 1;
        $sort        = $queryParams['sort'] ?? 'ASC';
        $rand        = $queryParams['rand'] ?? '';

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW IdeaListDTO(p.id, ct.name, ct.rgb, p.title, p.description, cl.name) as idea')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->leftJoin('p.campaignLocation', 'cl')
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

        if ($theme && $theme !== 0) {
            $qb->andWhere('ct.code = :themes');
            $qb->setParameter('themes', strtoupper($theme));
        }

        if ($location && $location !== 0) {
            $qb->andWhere('cl.id = :location');
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

        $qb->setMaxResults(1);

        $paginator = new IdeaCollection($qb);
        $paginator->setUseOutputWalkers(false);

        $paginator->getQuery()->setFirstResult($this->pageCount * $page)->setMaxResults($this->pageCount);

        try {
            $resource = $this->resourceGenerator->fromObject($paginator, $request);
        } catch (ResourceGenerator\Exception\OutOfBoundsException $e) {
            return new JsonResponse([
                'errors' => 'Bad Request',
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => 'Bad Request',
            ], 400);
        }

        return $this->responseFactory->createResponse($request, $resource);
    }
}
