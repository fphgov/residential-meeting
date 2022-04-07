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

use function array_merge_recursive;

final class ModifyHandler implements RequestHandlerInterface
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
        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $entityRepository = $this->implementationService->getRepository();

        $implementation = $entityRepository->find($request->getAttribute('id'));

        if ($implementation === null) {
            return new JsonResponse([
                'errors' => 'Nincs ilyen azonosítójú megvalósítás, vagy még feldolgozás alatt áll',
            ], 404);
        }

        try {
            $this->implementationService->modifyImplementation($implementation, $body);
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'message' => 'Sikeres a megvalósítás módosítása',
        ]);
    }
}
