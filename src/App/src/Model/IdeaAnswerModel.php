<?php

declare(strict_types=1);

namespace App\Model;

final class IdeaAnswerModel
{
    private int $id;
    private string $answer;
    private int $workflowStateId;
    private ?int $workflowStateExtraId;

    public function __construct(array $ideaAnswer)
    {
        $this->id                   = $ideaAnswer['A'];
        $this->answer               = (string) $ideaAnswer['B'];
        $this->workflowStateId      = (int) $ideaAnswer['C'];
        $this->workflowStateExtraId = $ideaAnswer['D'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getWorkflowStateId(): int
    {
        return $this->workflowStateId;
    }

    public function getWorkflowStateExtraId(): ?int
    {
        return $this->workflowStateExtraId;
    }
}
