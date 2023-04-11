<?php

declare(strict_types=1);

namespace App\Service;

interface QuestionServiceInterface
{
    public function getQuestions(): array;
}
