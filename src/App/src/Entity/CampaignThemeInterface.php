<?php

declare(strict_types=1);

namespace App\Entity;

interface CampaignThemeInterface {
    public function getCode(): string;

    public function setCode(string $code);

    public function getName(): string;

    public function setName(string $name);

    public function getDescription(): string;

    public function setDescription(string $description);

    public function getRgb(): string;

    public function setRgb(string $rgb);
}
