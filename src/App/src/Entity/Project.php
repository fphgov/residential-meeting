<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

use function array_slice;
use function count;
use function explode;
use function implode;
use function min;
use function strip_tags;
use function trim;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project implements JsonSerializable, ProjectInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignTheme")
     * @ORM\JoinColumn(name="campaign_theme_id", referencedColumnName="id", nullable=false)
     *
     * @var CampaignTheme
     */
    private $campaignTheme;

    /**
     * @ORM\OneToMany(targetEntity="Idea", mappedBy="project")
     *
     * @var Collection|Idea[]
     */
    private $ideas;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="projects_tags",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     *
     * @var Collection|Tag[]
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="CampaignLocation")
     * @ORM\JoinTable(name="projects_campaign_locations",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="campaign_location_id", referencedColumnName="id")}
     * )
     *
     * @var Collection|CampaignLocation[]
     */
    private $campaignLocations;

    /**
     * @ORM\ManyToMany(targetEntity="Media")
     * @ORM\JoinTable(name="projects_medias",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
     * )
     *
     * @var Collection|Media[]
     */
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="WorkflowState")
     * @ORM\JoinColumn(name="workflow_state_id", referencedColumnName="id", nullable=false)
     *
     * @var WorkflowState
     */
    private $workflowState;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="location", type="string")
     *
     * @var string
     */
    private $location;

    /**
     * @ORM\Column(name="solution", type="text")
     *
     * @var string
     */
    private $solution;

    /**
     * @ORM\Column(name="cost", type="bigint", options={"unsigned"=true}, nullable=true)
     *
     * @var string|null
     */
    private $cost;

    /**
     * @ORM\Column(name="video", type="string", nullable=true)
     *
     * @var string|null
     */
    private $video;

    /**
     * @ORM\Column(name="win", type="boolean", nullable=false)
     *
     * @var bool
     */
    private $win = false;

    public function getCampaignTheme(): CampaignTheme
    {
        return $this->campaignTheme;
    }

    public function setCampaignTheme(CampaignTheme $campaignTheme): void
    {
        $this->campaignTheme = $campaignTheme;
    }

    public function getMedias(): array
    {
        $medias = [];
        foreach ($this->medias->getValues() as $media) {
            $medias[] = $media->getId();
        }

        return $medias;
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

    /** @var int|string|null $cost **/
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
}
