<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\InputFilter\IdeaInputFilter;
use App\Middleware\UserMiddleware;
use App\Service\IdeaServiceInterface;
use App\Service\SettingServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge_recursive;

final class IdeaHandler implements RequestHandlerInterface
{
    /** @var IdeaServiceInterface **/
    private $ideaService;

    /** @var IdeaInputFilter **/
    private $ideaInputFilter;

    /** @var SettingServiceInterface */
    private $settingService;

    public function __construct(
        IdeaServiceInterface $ideaService,
        IdeaInputFilter $ideaInputFilter,
        SettingServiceInterface $settingService
    ) {
        $this->ideaService     = $ideaService;
        $this->ideaInputFilter = $ideaInputFilter;
        $this->settingService  = $settingService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $setting = $this->settingService->getRepository()->find(1);

        $this->ideaInputFilter->setData($body);

        if (! $this->ideaInputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->ideaInputFilter->getMessages(),
            ], 422);
        }

        $filteredParams = $this->ideaInputFilter->getValues();

        try {
            $this->ideaService->addIdea($user, $filteredParams);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Sikertelen ötlet beküldés',
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Sikeres ötlet beküldés',
        ]);
    }
}
