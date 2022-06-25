<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Service\VoteServiceInterface;
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
        $projects = $this->voteService->getVoteablesProjects();

        return new JsonResponse([
            'data' => $projects,
        ]);
    }
}
