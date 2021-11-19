<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\IdeaCollection;
use App\Entity\Campaign;
use App\Entity\CampaignTheme;
use App\Entity\WorkflowState;
use App\Entity\User;
use App\Service\IdeaServiceInterface;
use Doctrine\ORM\Query\Expr\Join;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function is_string;
use function strtoupper;
use function explode;

final class AdminListHandler implements RequestHandlerInterface
{
    /** @var IdeaServiceInterface */
    private $ideaService;

    /** @var int */
    protected $pageCount;

    /** @var HalResponseFactory */
    protected $responseFactory;

    /** @var ResourceGenerator */
    protected $resourceGenerator;

    public function __construct(
        IdeaServiceInterface $ideaService,
        int $pageCount,
        HalResponseFactory $responseFactory,
        ResourceGenerator $resourceGenerator
    ) {
        $this->ideaService       = $ideaService;
        $this->pageCount         = $pageCount;
        $this->responseFactory   = $responseFactory;
        $this->resourceGenerator = $resourceGenerator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body        = $request->getParsedBody();
        $queryParams = $request->getQueryParams();

        $page     = $queryParams['page'] ?? 1;

        $sort     = $body['sort'] ?? 'DESC';
        $theme    = $body['theme'] ?? '';
        $location = $body['location'] ?? '';
        $campaign = $body['campaign'] ?? '';
        $status   = $body['status'] ?? '';

        $searchWord = $body['search'];

        if (! isset($body['search'])) {
            return new JsonResponse([], 204);
        }

        $qb = $this->ideaService->getRepository()->createQueryBuilder('p')
            ->select('NEW IdeaListDTO(p.id, c.shortTitle, ct.name, ct.rgb, p.title, p.description, w.code, w.title, cl.name) as idea')
            ->join(CampaignTheme::class, 'ct', Join::WITH, 'ct.id = p.campaignTheme')
            ->join(Campaign::class, 'c', Join::WITH, 'c.id = p.campaign')
            ->join(WorkflowState::class, 'w', Join::WITH, 'w.id = p.workflowState')
            ->innerJoin(User::class, 'u', Join::WITH, 'u.id = p.submitter')
            ->leftJoin('p.campaignLocation', 'cl')
            ->groupBy('p.id');

        $qb->orderBy('p.id', $sort);

        if (intval($searchWord) !== 0) {
            $qb->where('p.id = :id')->setParameter('id', $searchWord);
        } elseif ($searchWord) {
            $words = explode(' ', $searchWord);

            foreach ($words as $word) {
                $qb->where('p.title LIKE :title')
                    ->orWhere('p.description LIKE :description')
                    ->orWhere('p.solution LIKE :solution')
                    ->setParameter('title', '%' . $word . '%')
                    ->setParameter('description', '%' . $word . '%')
                    ->setParameter('solution', '%' . $word . '%');
            }
        }

        if ($theme && $theme !== 0) {
            $qb->andWhere('ct.code = :themes');
            $qb->setParameter('themes', strtoupper($theme));
        }

        if ($location && is_string($location) && $location !== 0) {
            $qb->andWhere('cl.code = :location');
            $qb->setParameter('location', $location);
        }

        if ($campaign && $campaign !== 0) {
            $qb->andWhere('ct.campaign = :campaign');
            $qb->setParameter('campaign', $campaign);
        }

        if ($status && $status !== 0) {
            $qb->andWhere('w.code IN (:status)');
            $qb->setParameter('status', strtoupper($status));
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
