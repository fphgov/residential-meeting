<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Exception\DifferentPhaseException;
use App\Middleware\UserMiddleware;
use App\Service\IdeaServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge_recursive;

final class IdeaHandler implements RequestHandlerInterface
{
    /** @var IdeaServiceInterface **/
    private $ideaService;

    /** @var InputFilterInterface **/
    private $ideaInputFilter;

    public function __construct(
        IdeaServiceInterface $ideaService,
        InputFilterInterface $ideaInputFilter
    ) {
        $this->ideaService     = $ideaService;
        $this->ideaInputFilter = $ideaInputFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        $body = array_merge_recursive(
            $request->getParsedBody(),
            $request->getUploadedFiles(),
        );

        $this->ideaInputFilter->setData($body);

        if (! $this->ideaInputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->ideaInputFilter->getMessages(),
            ], 422);
        }

        $filteredParams = $this->ideaInputFilter->getValues();

        try {
            $this->ideaService->addIdea($user, $filteredParams);
        } catch (DifferentPhaseException $e) {
            return new JsonResponse([
                'message' => 'Jelenleg nem lehetséges az ötlet beküldése',
            ], 422);
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
