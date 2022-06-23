<?php

declare(strict_types=1);

namespace App\Handler\Implementation;

use App\Middleware\UserMiddleware;
use App\Service\ImplementationService;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DeleteHandler implements RequestHandlerInterface
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
        $user = $request->getAttribute(UserMiddleware::class);

        $entityRepository = $this->implementationService->getRepository();

        $implementation = $entityRepository->find($request->getAttribute('id'));

        if ($implementation === null) {
            return new JsonResponse([
                'errors' => 'Nincs ilyen azonosítójú megvalósítás, vagy még feldolgozás alatt áll',
            ], 404);
        }

        try {
            $this->implementationService->deleteImplementation($user, $implementation);
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'message' => 'A megvalósítás törlése sikeres',
        ]);
    }
}
