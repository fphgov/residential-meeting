<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

interface LinkInterface
{
    public const DISABLE_SHOW_DEFAULT = [];

    public const DISABLE_DEFAULT_SET = [];

    public function getId(): UuidInterface;

    public function setId(UuidInterface $id): void;

    public function getIdea(): Idea;

    public function setIdea(Idea $idea): void;

    public function setHref(string $href): void;

    public function getHref(): string;
}
