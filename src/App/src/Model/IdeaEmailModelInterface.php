<?php

declare(strict_types=1);

namespace App\Model;

use Mail\Model\EmailContentModelInterface;

interface IdeaEmailModelInterface
{
    public function getId(): int;

    public function getEmailContent(): EmailContentModelInterface;

    public function getWorkflowStateId(): int;

    public function getWorkflowStateExtraId(): ?int;
}
