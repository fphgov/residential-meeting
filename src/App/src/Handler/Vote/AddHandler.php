<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Exception\AccountNotVotableException;
use App\Exception\CloseCampaignException;
use App\Middleware\AccountMiddleware;
use App\Service\VoteServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AddHandler implements RequestHandlerInterface
{
    public function __construct(
        private VoteServiceInterface $voteService,
        private InputFilterInterface $voteFilter
    ) {
        $this->voteService = $voteService;
        $this->voteFilter  = $voteFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $account = $request->getAttribute(AccountMiddleware::class);

        $body = $request->getParsedBody();

        $this->voteFilter->setData($body);

        if (! $this->voteFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->voteFilter->getMessages(),
            ], 422);
        }

        try {
            $this->voteService->voting($account, $this->voteFilter->getValues());
        } catch (CloseCampaignException $e) {
            return new JsonResponse([
                'error' => 'A szavazás jelenleg zárva tart',
            ], 422);
        } catch (AccountNotVotableException $e) {
            return new JsonResponse([
                'error' => 'Már leadtad a szavazatod',
            ], 422);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Sikertelen szavazás',
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Sikeres szavazás',
        ]);
    }
}
