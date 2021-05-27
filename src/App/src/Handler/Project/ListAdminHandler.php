<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Project;
use Doctrine\ORM\EntityManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ListAdminHandler implements RequestHandlerInterface
{
    /** @var EntityManager */
    private $em;

    public function __construct(
        EntityManager $em
    ) {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $projectRepository = $this->em->getRepository(Project::class);

        $projects = $projectRepository->getForSelection();

        return new JsonResponse([
            'projects' => $projects,
        ]);
    }
}
