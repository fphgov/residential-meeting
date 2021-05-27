<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Middleware\UserMiddleware;
use App\Service\VoteServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AddHandler implements RequestHandlerInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var VoteServiceInterface */
    private $voteService;

    public function __construct(
        InputFilterInterface $inputFilter,
        VoteServiceInterface $voteService
    ) {
        $this->inputFilter = $inputFilter;
        $this->voteService = $voteService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        $this->inputFilter->setData($request->getParsedBody());

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages(),
            ], 422);
        }

        try {
            $vote = $this->voteService->addOfflineVote($user, $this->inputFilter->getValues());
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
