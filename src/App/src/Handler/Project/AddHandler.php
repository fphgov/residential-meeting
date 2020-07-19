<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Service\UserServiceInterface;
use App\Service\ProjectServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

final class AddHandler implements RequestHandlerInterface
{
    /** @var UserServiceInterface **/
    private $userService;

    /** @var ProjectServiceInterface */
    private $projectService;

    /**
     * @param UserServiceInterface $userService
     * @param ProjectServiceInterface $projectService
     */
    public function __construct(
        UserServiceInterface $userService,
        ProjectServiceInterface $projectService
    ) {
        $this->userService    = $userService;
        $this->projectService = $projectService;
    }

    public function handle(
        ServerRequestInterface $request
    ) : ResponseInterface {
        $token = $request->getAttribute(JwtAuthMiddleware::class);

        $user = $this->userService->getRepository()->findOneBy([
            'email' => $token['user']->email,
        ]);

        $projects = $this->projectService->findAllForView(false);

        // $request->getParsedBody()

        return new JsonResponse([
            'data' => $projects,
        ]);
    }
}
