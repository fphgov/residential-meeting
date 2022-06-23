<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Http\Message\StreamInterface;

interface IdeaAnswerServiceInterface
{
    public function importIdeaAnswers(StreamInterface $stream);
}
