<?php

declare(strict_types=1);

namespace App\Handler\Tools;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use FphGov\Arcgis\Service\ArcgisServiceInterface;

final class GetAddressHandler implements RequestHandlerInterface
{
    /** @var ArcgisServiceInterface */
    private $acrgisService;

    public function __construct(
        ArcgisServiceInterface $acrgisService
    ) {
        $this->acrgisService = $acrgisService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        if (! isset($body['address'])) {
            return new JsonResponse([], 204);
        }

        $results = $this->acrgisService->findAddress($body['address']);

        return new JsonResponse([
            'data' => $results,
        ]);
    }
}
