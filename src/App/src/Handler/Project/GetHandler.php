<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\OfflineVote;
use App\Entity\Project;
use App\Entity\Vote;
use App\Entity\WorkflowStateInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;

final class GetHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var HalResponseFactory */
    protected $responseFactory;

    /** @var ResourceGenerator */
    protected $resourceGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        HalResponseFactory $responseFactory,
        ResourceGenerator $resourceGenerator
    ) {
        $this->entityManager     = $entityManager;
        $this->responseFactory   = $responseFactory;
        $this->resourceGenerator = $resourceGenerator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $entityRepository      = $this->entityManager->getRepository(Project::class);
        $voteRepository        = $this->entityManager->getRepository(Vote::class);
        $offlineVoteRepository = $this->entityManager->getRepository(OfflineVote::class);

        $result = $entityRepository->find($request->getAttribute('id'));
        $count  = $voteRepository->numberOfVotes((int) $request->getAttribute('id'));
        $count += $offlineVoteRepository->numberOfVotes((int) $request->getAttribute('id'));

        if ($result === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $project = $result->normalizer(null, ['groups' => 'detail']);

        $resource = $this->resourceGenerator->fromArray($project, null);

        if (
            in_array($result->getWorkflowState()->getId(), [
                WorkflowStateInterface::STATUS_PRE_COUNCIL
            ], true)
        ) {
            $resource = $resource->withElement('voted', null);
        } else {
            $resource = $resource->withElement('voted', $count);
        }


        return $this->responseFactory->createResponse($request, $resource);
    }
}
