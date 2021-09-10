<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Ramsey\Uuid\UuidInterface;

interface MediaInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public function getId(): UuidInterface;

    public function setId(UuidInterface $id): UuidInterface;

    public function setFilename(string $filename): void;

    public function getFilename(): string;

    public function setType(?string $type = null): void;

    public function getType(): ?string;

    public function getCreatedAt(): DateTime;

    public function setCreatedAt(DateTime $createdAt): void;

    public function getUpdatedAt(): DateTime;

    public function setUpdatedAt(DateTime $updatedAt): void;
}
