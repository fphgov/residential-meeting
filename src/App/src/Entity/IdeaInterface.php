<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface IdeaInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'campaignTheme',
        'campaignLocation',
        'submitter',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function getSubmitter(): UserInterface;

    public function setSubmitter(UserInterface $submitter): void;

    public function getCampaign(): CampaignInterface;

    public function setCampaign(CampaignInterface $campaign): void;

    public function getCampaignTheme(): CampaignThemeInterface;

    public function setCampaignTheme(CampaignThemeInterface $campaignTheme): void;

    public function getCampaignLocation(): ?CampaignLocationInterface;

    public function setCampaignLocation(?CampaignLocationInterface $campaignLocation = null): void;

    public function getProject(): ?ProjectInterface;

    public function setProject(?ProjectInterface $project = null): void;

    public function getMedias(): array;

    public function getMediaCollection(): Collection;

    public function addMedia(MediaInterface $media): self;

    public function removeMedia(MediaInterface $media): self;

    public function getLinks(): array;

    public function addLink(Link $link): self;

    public function removeLink(Link $link): self;

    public function getCommentCollection(): Collection;

    public function getComments(): array;

    public function addComment(CommentInterface $comment): self;

    public function setWorkflowState(WorkflowState $workflowState): void;

    public function getWorkflowState(): WorkflowState;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setLocationDescription(string $locationDescription): void;

    public function getLocationDescription(): string;

    public function getShortDescription(): string;

    public function setLatitude(?float $latitude = null): void;

    public function getLatitude(): ?float;

    public function setLongitude(?float $longitude = null): void;

    public function getLongitude(): ?float;

    public function setSolution(string $solution): void;

    public function getSolution(): string;

    public function setParticipate(bool $participate): void;

    public function getParticipate(): bool;

    public function setParticipateComment(string $participateComment): void;

    public function getParticipateComment(): string;

    /** @var int|string|null $cost **/
    public function setCost($cost = null): void;

    public function getCost(): ?int;

    public function setAnswer(string $answer): void;

    public function getAnswer(): string;
}
