<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Question;
use App\Repository\QuestionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class QuestionService implements QuestionServiceInterface
{
    protected QuestionRepositoryInterface $questionRepository;

    public function __construct(
        protected EntityManagerInterface $em
    ) {
        $this->em                 = $em;
        $this->questionRepository = $this->em->getRepository(Question::class);
    }

    public function getQuestions(): array
    {
        return $this->questionRepository->findAll([
            'active' => true,
        ]);
    }
}
