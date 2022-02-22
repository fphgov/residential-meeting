<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\Campaign;
use App\Entity\CampaignTheme;
use App\Entity\Idea;
use App\Entity\IdeaCollection;
use App\Entity\User;
use App\Entity\WorkflowState;
use App\Entity\WorkflowStateInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function explode;
use function implode;
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
        $repository = $this->em->getRepository(Idea::class);

        $queryParams = $request->getQueryParams();
        $username    = $queryParams['username'] ?? '';
        $ids         = $queryParams['ids'] ?? '';
        $query       = $queryParams['query'] ?? '';
        $theme       = $queryParams['theme'] ?? '';
        $location    = $queryParams['location'] ?? '';
        $campaign    = $queryParams['campaign'] ?? '';
        $page        = $queryParams['page'] ?? 1;
        $sort        = $queryParams['sort'] ?? 'ASC';
        $rand        = $queryParams['rand'] ?? '';
        $status      = $queryParams['status'] ?? '';

        $qb = $repository->createQueryBuilder('p')
            ->select('NEW IdeaListDTO(p.id, c.shortTitle, ct.code, ct.name, ct.rgb, p.title, p.description, w.id, w.code, w.title, CONCAT_WS(\' \', u.lastname, u.firstname), cl.name) as idea')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->join(Campaign::class, 'c', Join::WITH, 'c.id = p.campaign')
            ->join(WorkflowState::class, 'w', Join::WITH, 'w.id = p.workflowState')
            ->innerJoin(User::class, 'u', Join::WITH, 'u.id = p.submitter')
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

            if ($status === 'published') {
                $status .= ', published_with_mod';
                $status  = strtoupper($status);

                $qb->setParameter('status', explode(', ', $status));
            } else {
                $qb->setParameter('status', strtoupper($status));
            }
        }

        $disableStatuses = implode(', ', [
            WorkflowStateInterface::STATUS_RECEIVED,
            WorkflowStateInterface::STATUS_USER_DELETED,
            WorkflowStateInterface::STATUS_TRASH,
        ]);

        if ($username && $username !== '') {
            $qb->andWhere('u.username = :username');
            $qb->setParameter('username', $username);
        } else {
            $qb->andWhere('w.id NOT IN (' . $disableStatuses . ')');
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
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => 'Bad Request',
            ], 400);
        }

        return $this->responseFactory->createResponse($request, $resource);
    }
}
