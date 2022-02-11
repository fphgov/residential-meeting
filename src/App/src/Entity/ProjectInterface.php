<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface ProjectInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'campaignLocation',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getCampaign(): CampaignInterface;

    public function setCampaign(CampaignInterface $campaign): void;

    public function getCampaignTheme(): CampaignTheme;

    public function setCampaignTheme(CampaignTheme $campaignTheme): void;

    public function getMedias(): array;

    public function getMediaCollection(): Collection;

    public function addMedia(MediaInterface $media): self;

    public function removeMedia(MediaInterface $media): self;

    public function getIdeas(): array;

    public function getIdeaCollection(): Collection;

    public function getTags(): array;

    public function getTagCollection(): Collection;

    public function addTag(Tag $tag): self;

    public function removeTag(Tag $tag): self;

    public function getCampaignLocations(): array;

    public function addCampaignLocation(CampaignLocation $campaignLocation): self;

    public function removeCampaignLocation(CampaignLocation $campaignLocation): self;

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

    /** @var int|string|null $cost **/
    public function setCost($cost = null): void;

    public function getCost(): ?int;

    public function setWorkflowState(WorkflowState $workflowState): void;

    public function getWorkflowState(): WorkflowState;

    public function setWin(bool $win): void;

    public function getWin(): bool;

    public function getShortDescription(): string;

    public function setLatitude(?float $latitude = null): void;

    public function getLatitude(): ?float;

    public function setLongitude(?float $longitude = null): void;

    public function getLongitude(): ?float;
}
