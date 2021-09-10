<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntityInterface;

interface CampaignThemeInterface extends EntityInterface, EntityActiveInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getCampaign(): Campaign;

    public function setCampaign(Campaign $campaign): void;

    public function getCode(): string;

    public function setCode(string $code): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getDescription(): string;

    public function setDescription(string $description): void;

    public function getRgb(): string;

    public function setRgb(string $rgb): void;
}
