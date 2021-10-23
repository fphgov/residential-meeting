<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\Idea;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $entityRepository = $this->entityManager->getRepository(Idea::class);

        $result = $entityRepository->find($request->getAttribute('id'));

        if ($result === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $idea = $result->normalizer(null, ['groups' => 'detail']);

        $resource = $this->resourceGenerator->fromArray($idea, null);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
