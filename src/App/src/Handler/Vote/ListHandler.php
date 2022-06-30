<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Service\VoteServiceInterface;
use App\Exception\DifferentPhaseException;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ListHandler implements RequestHandlerInterface
{
    /** @var VoteServiceInterface */
    private $voteService;

    public function __construct(VoteServiceInterface $voteService)
    {
        $this->voteService = $voteService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $rand        = $queryParams['rand'] ?? '';

        try {
            $projects = $this->voteService->getVoteablesProjects($rand);
        } catch (DifferentPhaseException $e) {
            return new JsonResponse([
                'message' => 'A szavazás zárva',
                'code'    => 'CLOSED'
            ], 422);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Nem várt hiba történt',
                'code'    => 'SERVER_ERROR'
            ], 500);
        }

        return new JsonResponse([
            'data' => $projects,
        ]);
    }
}
