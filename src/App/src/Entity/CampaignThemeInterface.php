<?php

declare(strict_types=1);

namespace App\Entity;

interface CampaignThemeInterface
{
    public function getCode(): string;

    public function setCode(string $code): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getDescription(): string;

    public function setDescription(string $description): void;

    public function getRgb(): string;

    public function setRgb(string $rgb): void;
}
