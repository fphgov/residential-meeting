<?php

declare(strict_types=1);

namespace App\Handler\Phase;

use App\Service\PhaseServiceInterface;
use App\Exception\NoHasPhaseException;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CheckHandler implements RequestHandlerInterface
{
    /** @var PhaseServiceInterface */
    private $phaseService;

    public function __construct(PhaseServiceInterface $phaseService)
    {
        $this->phaseService = $phaseService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $phase = $this->phaseService->getCurrentPhase();
        } catch (NoHasPhaseException $e) {
            return new JsonResponse([
                'message' => 'A szavazás zárva',
                'code'    => 'CLOSED'
            ], 422);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Nem várt hiba történt',
                'code'    => 'SERVER_ERROR'
            ], 422);
        }

        return new JsonResponse([
            'data' => [
                'code' => $phase->getCode(),
            ],
        ]);
    }
}
