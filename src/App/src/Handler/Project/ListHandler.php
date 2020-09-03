<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\ProjectServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ListHandler implements RequestHandlerInterface
{
    /** @var ProjectServiceInterface **/
    private $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $projects = $this->projectService->getRepository()->findby([], [
            'createdAt' => 'DESC',
        ]);

        return new JsonResponse($projects);
    }
}
