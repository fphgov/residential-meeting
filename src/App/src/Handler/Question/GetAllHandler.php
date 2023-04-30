<?php

declare(strict_types=1);

namespace App\Handler\Question;

use App\Entity\Question;
use App\Repository\QuestionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetAllHandler implements RequestHandlerInterface
{
    /** @var QuestionRepositoryInterface **/
    private $questionRepository;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $this->em                 = $em;
        $this->questionRepository = $this->em->getRepository(Question::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $questions = $this->questionRepository->findAll([
            'active' => true,
        ]);

        $normalizedQuestions = [];

        foreach ($questions as $question) {
            $normalizedQuestions[] = $question->normalizer(null, ['groups' => 'full_detail']);
        }

        return new JsonResponse([
            'questions' => $normalizedQuestions,
        ]);
    }
}
