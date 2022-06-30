<?php

declare(strict_types=1);

namespace App\Handler\Vote;

use App\Middleware\UserMiddleware;
use App\Service\VoteValidationServiceInterface;
use App\Service\PhaseServiceInterface;
use App\Exception\VoteUserExistsException;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CheckHandler implements RequestHandlerInterface
{
    /** @var VoteValidationServiceInterface */
    private $voteValidationService;

    /** @var PhaseServiceInterface */
    private $phaseService;

    public function __construct(
        VoteValidationServiceInterface $voteValidationService,
        PhaseServiceInterface $phaseService
    )
    {
        $this->voteValidationService = $voteValidationService;
        $this->phaseService          = $phaseService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        if ($user) {
            try {
                $phase = $this->phaseService->getCurrentPhase();

                $this->voteValidationService->checkExistsVote($user, $phase);
            } catch (VoteUserExistsException $e) {
                return new JsonResponse([
                    'message' => 'Idén már leadtad a szavazatod',
                    'code'    => 'ALREADY_EXISTS'
                ], 409);
            } catch (Exception $e) {
                return new JsonResponse([
                    'message' => 'Nem várt hiba történt',
                    'code'    => 'SERVER_ERROR'
                ], 500);
            }
        }

        return new JsonResponse([
            'data' =>  [
                'code' => 'OK',
            ],
        ]);
    }
}
