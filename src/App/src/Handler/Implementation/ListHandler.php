<?php

declare(strict_types=1);

namespace App\Handler\Implementation;

use App\Entity\Implementation;
use App\Service\ImplementationService;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ListHandler implements RequestHandlerInterface
{
    /** @var ImplementationService */
    private $implementationService;

    public function __construct(
        ImplementationService $implementationService
    ) {
        $this->implementationService = $implementationService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $entityRepository = $this->implementationService->getRepository();

        if (! isset($queryParams['project'])) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $implementations = $entityRepository->findBy([
            'project' => $queryParams['project']
        ]);

        if ($implementations === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $normalizedImplementations = [];
        foreach ($implementations as $implementation) {
            $normalizedImplementations[] = $implementation->normalizer(null, ['groups' => 'detail']);
        }

        return new JsonResponse([
            'data' => $normalizedImplementations,
        ]);
    }
}
