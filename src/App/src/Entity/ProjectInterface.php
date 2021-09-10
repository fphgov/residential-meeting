<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface ProjectInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'campaignLocation',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getCampaignTheme(): CampaignTheme;

    public function setCampaignTheme(CampaignTheme $campaignTheme): void;

    public function getMedias(): array;

    public function getIdeas(): array;

    public function getTags(): array;

    public function getTagCollection(): Collection;

    public function addTag(Tag $tag): self;

    public function removeTag(Tag $tag): self;

    public function getCampaignLocations(): array;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setLocation(string $location): void;

    public function getLocation(): string;

    public function setSolution(string $solution): void;

    public function getSolution(): string;

    public function setVideo(?string $video = null): void;

    public function getVideo(): ?string;

    public function setCost(?string $cost = null): void;

    public function getCost(): ?int;

    public function setStatus(int $status): void;

    public function getStatus(): int;

    public function setWin(bool $win): void;

    public function getWin(): bool;

    public function getShortDescription(): string;
}
