<?php

declare(strict_types=1);

namespace Mail\Helper;

interface MailContentHelperInterface
{
    public function create(string $name, array $variables): self;

    public function render(string $type): string;

    public function setName(string $name): void;

    public function getName(): string;
}
