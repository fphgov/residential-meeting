<?php

declare(strict_types=1);

namespace App\Interfaces;

use DateTime;

interface EntityInterface
{
    public function getId(): int;

    public function setId(int $id): void;

    public function getCreatedAt(): DateTime;

    public function setCreatedAt(DateTime $createdAt): void;

    public function getUpdatedAt(): DateTime;

    public function setUpdatedAt(DateTime $updatedAt): void;

    public function setProps(array $datas): void;

    public function getProps(): array;

    public function jsonSerialize(): array;

    public function toArray(): array;
}
