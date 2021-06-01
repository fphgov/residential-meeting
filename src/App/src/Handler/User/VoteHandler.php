<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\VoteServiceInterface;
use App\InputFilter\VoteFilter;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

final class VoteHandler implements RequestHandlerInterface
{
    /** @var VoteServiceInterface **/
    private $voteService;

    /** @var VoteFilter **/
    private $voteFilter;

    public function __construct(
        VoteServiceInterface $voteService,
        VoteFilter $voteFilter
    ) {
        $this->voteService = $voteService;
        $this->voteFilter  = $voteFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(\App\Middleware\UserMiddleware::class);
        $body = $request->getParsedBody();

        $existsVote = $this->voteService->getRepository()->findOneBy([
            'user' => $user->getId()
        ]);

        if ($existsVote) {
            return new JsonResponse([
                'message' => 'Már leadta a végleges szavazatát',
            ], 422);
        }

        $this->voteFilter->setData($body);

        if (! $this->voteFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->voteFilter->getMessages(),
            ], 422);
        }

        try {
            $this->voteService->voting($user, $body);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Sikertelen szavazás',
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Sikeres szavazás',
        ]);
    }
}
