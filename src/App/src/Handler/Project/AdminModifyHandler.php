<?php

declare(strict_types=1);

namespace App\Handler\Project;

use App\Entity\Project;
use App\Service\ProjectServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AdminModifyHandler implements RequestHandlerInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var ProjectServiceInterface */
    protected $projectService;

    public function __construct(
        InputFilterInterface $inputFilter,
        EntityManagerInterface $em,
        ProjectServiceInterface $projectService
    ) {
        $this->inputFilter    = $inputFilter;
        $this->em             = $em;
        $this->projectService = $projectService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $entityRepository = $this->em->getRepository(Project::class);

        $project = $entityRepository->find($request->getAttribute('id'));

        if ($project === null) {
            return new JsonResponse([
                'errors' => 'Nincs ilyen azonosítójú ötlet, vagy még feldolgozás alatt áll',
            ], 404);
        }

        // $modifiedProjectData = array_merge($project->normalizer(null, ['groups' => 'full_detail']), $body);

        // $this->inputFilter->setData($modifiedProjectData);

        // if (! $this->inputFilter->isValid()) {
        //     return new JsonResponse([
        //         'errors' => $this->inputFilter->getMessages(),
        //     ], 422);
        // }

        try {
            // $this->projectService->modifyProject($project, $this->inputFilter->getValues());
            $this->projectService->modifyProject($project, $body);
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }
}
