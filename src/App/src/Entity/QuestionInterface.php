<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntitySimpleInterface;

interface QuestionInterface extends EntitySimpleInterface, EntityActiveInterface
{
    public function setQuestion(string $question): void;

    public function getQuestion(): string;

    public function setOptionYesLabel(string $optionYesLabel): void;

    public function getOptionYesLabel(): string;

    public function setOptionNoLabel(string $optionNoLabel): void;

    public function getOptionNoLabel(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setDescriptionOptionYes(string $descriptionOptionYes): void;

    public function getDescriptionOptionYes(): string;

    public function setDescriptionOptionNo(string $descriptionOptionNo): void;

    public function getDescriptionOptionNo(): string;
}
