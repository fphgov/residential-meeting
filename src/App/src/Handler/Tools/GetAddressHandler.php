<?php

declare(strict_types=1);

namespace App\Handler\Tools;

use App\Service\PostalClientServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetAddressHandler implements RequestHandlerInterface
{
    /** @var PostalClientServiceInterface */
    private $postalClientService;

    public function __construct(
        PostalClientServiceInterface $postalClientService
    ) {
        $this->postalClientService = $postalClientService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        if (! isset($body['address'])) {
            return new JsonResponse([], 204);
        }

        $results = $this->postalClientService->getAddress($body['address']);

        return new JsonResponse([
            'data' => $results,
        ]);
    }
}
