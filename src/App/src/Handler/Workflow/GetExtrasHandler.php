<?php

declare(strict_types=1);

namespace App\Handler\Workflow;

use App\Entity\WorkflowStateExtra;
use App\Middleware\UserMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetExtrasHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $workflowStateExtraRepository = $this->em->getRepository(WorkflowStateExtra::class);

        $normalizedWorkFlowStates = $workflowStateExtraRepository->getAllWorkflowStateExtra();

        return new JsonResponse([
            'data' => $normalizedWorkFlowStates,
        ]);
    }
}
