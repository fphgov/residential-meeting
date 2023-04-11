<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntitySimpleInterface;

interface VoteInterface extends EntitySimpleInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getQuestion(): Question;

    public function setQuestion(Question $question): void;

    public function getAnswer(): ?bool;

    public function setAnswer(?bool $answer): void;
}
