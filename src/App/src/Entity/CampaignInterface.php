<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface CampaignInterface extends EntityInterface, EntityActiveInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getIdeas(): array;

    public function getIdeaCollection(): Collection;

    public function getCampaignThemes(): array;

    public function getCampaignThemeCollection(): Collection;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setShortTitle(string $shortTitle): void;

    public function getShortTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;
}
