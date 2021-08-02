<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\VoteFilter;
use App\Middleware\UserMiddleware;
use App\Service\VoteServiceInterface;
use App\Service\SettingServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class VoteHandler implements RequestHandlerInterface
{
    /** @var VoteServiceInterface **/
    private $voteService;

    /** @var VoteFilter **/
    private $voteFilter;

    /** @var SettingServiceInterface */
    private $settingService;

    public function __construct(
        VoteServiceInterface $voteService,
        VoteFilter $voteFilter,
        SettingServiceInterface $settingService
    ) {
        $this->voteService    = $voteService;
        $this->voteFilter     = $voteFilter;
        $this->settingService = $settingService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);
        $body = $request->getParsedBody();

        $setting = $this->settingService->getRepository()->find(1);

        if ($setting && $setting->getClose()) {
            return new JsonResponse([
                'message' => 'A szavazás már lezárult',
            ], 422);
        }

        $existsVote = $this->voteService->getRepository()->findOneBy([
            'user' => $user->getId(),
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
