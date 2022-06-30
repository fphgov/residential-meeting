<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\EmailContentModel;
use Mail\Model\EmailContentModelInterface;

final class IdeaEmailModel implements IdeaEmailModelInterface
{
    private int $id;
    private EmailContentModelInterface $emailContent;
    private int $workflowStateId;
    private ?int $workflowStateExtraId = null;

    public function __construct(array $ideaEmail)
    {
        $this->id              = $ideaEmail['A'];
        $this->emailContent    = new EmailContentModel((string) $ideaEmail['B'], (string) $ideaEmail['C'], (string) $ideaEmail['D']);
        $this->workflowStateId = (int) $ideaEmail['E'];

        if (isset($ideaEmail['F'])) {
            $this->workflowStateExtraId = $ideaEmail['F'];
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmailContent(): EmailContentModelInterface
    {
        return $this->emailContent;
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
