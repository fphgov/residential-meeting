<?php

declare(strict_types=1);

namespace Mail\Helper;

interface MailContentHelperInterface
{
    public function create(string $code, array $variables): self;

    public function render(string $type): string;

    public function setCode(string $code): void;

    public function getCode(): string;
}
