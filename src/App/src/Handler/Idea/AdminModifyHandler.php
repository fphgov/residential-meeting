<?php

declare(strict_types=1);

namespace App\Handler\Idea;

use App\Entity\Idea;
use App\Service\IdeaServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

use function in_array;

final class AdminModifyHandler implements RequestHandlerInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var IdeaServiceInterface */
    protected $ideaService;

    public function __construct(
        InputFilterInterface $inputFilter,
        EntityManagerInterface $em,
        IdeaServiceInterface $ideaService
    ) {
        $this->inputFilter = $inputFilter;
        $this->em          = $em;
        $this->ideaService = $ideaService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $entityRepository = $this->em->getRepository(Idea::class);

        $idea = $entityRepository->find($request->getAttribute('id'));

        if ($idea === null) {
            return new JsonResponse([
                'errors' => 'Nincs ilyen azonosítójú ötlet, vagy még feldolgozás alatt áll',
            ], 404);
        }

        // $modifiedIdeaData = array_merge($idea->normalizer(null, ['groups' => 'full_detail']), $body);
        // $this->validation($modifiedIdeaData);

        try {
            // $this->ideaService->modifyIdea($idea, $this->inputFilter->getValues());
            $this->ideaService->modifyIdea($idea, $body);
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

    private function validation(array $data)
    {
        $this->inputFilter->setData($idea);

        if (! $this->inputFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->inputFilter->getMessages(),
            ], 422);
        }
    }
}
