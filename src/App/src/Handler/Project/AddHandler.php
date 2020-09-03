<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\UserServiceInterface;
use App\Service\ProjectServiceInterface;
use App\Middleware\UserMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Diactoros\Response\JsonResponse;

final class AddHandler implements RequestHandlerInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var ProjectServiceInterface */
    private $projectService;

    /**
     * @param InputFilterInterface    $inputFilter
     * @param ProjectServiceInterface $projectService
     */
    public function __construct(
        InputFilterInterface $inputFilter,
        ProjectServiceInterface $projectService
    ) {
        $this->inputFilter    = $inputFilter;
        $this->projectService = $projectService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface {
        $user = $request->getAttribute(UserMiddleware::class);

        $this->inputFilter->setData($request->getParsedBody());

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages()
            ], 422);
        }

        try {
            $project = $this->projectService->addProject($user, $this->inputFilter->getValues());
        } catch (\Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage()
            ], 500);
        }

        return new JsonResponse([
            'data' => $project,
        ]);
    }
}
