<?php

declare(strict_types=1);

namespace Mail\Model;

interface EmailTemplateRenderModelInterface
{
    public function getCode(): string;

    public function render(string $type): string;
}
