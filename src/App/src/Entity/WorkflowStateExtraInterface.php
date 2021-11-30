<?php

declare(strict_types=1);

namespace App\Entity;

interface WorkflowStateExtraInterface
{
    public const DISABLE_SHOW_DEFAULT = [];

    public const DISABLE_DEFAULT_SET = [];

    public function getId(): int;

    public function setId(int $id): void;

    public function setCode(string $code): void;

    public function getCode(): string;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;
}
