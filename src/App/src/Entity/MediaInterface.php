<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Ramsey\Uuid\UuidInterface;

interface MediaInterface
{
    public function getId(): UuidInterface;

    public function setId(UuidInterface $id): void;

    public function setFilename(string $filename): void;

    public function getFilename(): string;

    public function setType(?string $type = null): void;

    public function getType(): ?string;

    public function getExpirationDate(): ?DateTime;

    public function setExpirationDate(?DateTime $expirationDate = null): void;
}
