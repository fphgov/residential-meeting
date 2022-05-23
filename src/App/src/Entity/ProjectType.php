<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore as ignore;

use function array_slice;
use function count;
use function explode;
use function implode;
use function min;
use function strip_tags;
use function trim;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectTypeRepository")
 * @ORM\Table(name="project_types")
 */
class ProjectType implements ProjectInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $title;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $description;

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getCampaign(): CampaignInterface
    {
        return $this->campaign;
    }

    public function setCampaign(CampaignInterface $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function getCampaignTheme(): CampaignTheme
    {
        return $this->campaignTheme;
    }

    public function setCampaignTheme(CampaignTheme $campaignTheme): void
    {
        $this->campaignTheme = $campaignTheme;
    }

    public function getMediaCollection(): Collection
    {
        return $this->medias;
    }

    public function getMedias(): array
    {
        $medias = [];
        foreach ($this->medias->getValues() as $media) {
            $medias[] = [
                'id'   => $media->getId(),
                'type' => $media->getType(),
            ];
        }

        return $medias;
    }

    public function addMedia(MediaInterface $media): self
    {
        if (! $this->medias->contains($media)) {
            $this->medias[] = $media;
        }

        return $this;
    }

    public function removeMedia(MediaInterface $media): self
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
        }

        return $this;
    }

    public function getIdeaCollection(): Collection
    {
        return $this->ideas;
    }

    public function getIdeas(): array
    {
        $ideas = [];
        foreach ($this->ideas->getValues() as $idea) {
            $ideas[] = $idea->getId();
        }

        return $ideas;
    }

    public function getTags(): array
    {
        return $this->tags->getValues();
    }

    public function getTagCollection(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (! $this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function setWorkflowState(WorkflowState $workflowState): void
    {
        $this->workflowState = $workflowState;
    }

    public function getWorkflowState(): WorkflowState
    {
        return $this->workflowState;
    }

    public function getCampaignLocations(): array
    {
        return $this->campaignLocations->getValues();
    }

    public function addCampaignLocation(CampaignLocation $campaignLocation): self
    {
        if (! $this->campaignLocations->contains($campaignLocation)) {
            $this->campaignLocations[] = $campaignLocation;
        }

        return $this;
    }

    public function removeCampaignLocation(CampaignLocation $campaignLocation): self
    {
        if ($this->campaignLocations->contains($campaignLocation)) {
            $this->campaignLocations->removeElement($campaignLocation);
        }

        return $this;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setSolution(string $solution): void
    {
        $this->solution = $solution;
    }

    public function getSolution(): string
    {
        return $this->solution;
    }

    public function setVideo(?string $video = null): void
    {
        $this->video = $video;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    /** @param int|string|null $cost **/
    public function setCost($cost = null): void
    {
        $this->cost = $cost;
    }

    public function getCost(): ?int
    {
        return $this->cost !== null ? (int) $this->cost : null;
    }

    public function setWin(bool $win): void
    {
        $this->win = $win;
    }

    public function getWin(): bool
    {
        return $this->win;
    }

    public function getShortDescription(): string
    {
        $description = $this->description;

        $description = strip_tags($description);

        $descriptions = explode(" ", $description);
        $descriptions = array_slice($descriptions, 0, min(22, count($descriptions) - 1));

        $description  = trim(implode(" ", $descriptions));
        $description .= ' ...';

        return $description;
    }

    public function getImplementationCollection(): Collection
    {
        return $this->implementations;
    }

    public function getImplementations(): array
    {
        $implementations = [];
        foreach ($this->implementations->getValues() as $implementation) {
            $implementations[] = $implementation;
        }

        return $implementations;
    }

    public function addImplementation(ImplementationInterface $implementation): self
    {
        if (! $this->implementations->contains($implementation)) {
            $this->implementations[] = $implementation;
        }

        return $this;
    }

    public function setLatitude(?float $latitude = null): void
    {
        $this->latitude = $latitude;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLongitude(?float $longitude = null): void
    {
        $this->longitude = $longitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }
}
