<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

use function array_slice;
use function count;
use function explode;
use function implode;
use function min;
use function strip_tags;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdeaRepository")
 * @ORM\Table(name="ideas")
 */
class Idea implements JsonSerializable, IdeaInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign", inversedBy="ideas")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false)
     *
     * @var Campaign
     */
    private $campaign;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignTheme")
     * @ORM\JoinColumn(name="campaign_theme_id", referencedColumnName="id", nullable=false)
     *
     * @var CampaignTheme
     */
    private $campaignTheme;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignLocation")
     * @ORM\JoinColumn(name="campaign_location_id", referencedColumnName="id", nullable=true)
     *
     * @var CampaignLocation|null
     */
    private $campaignLocation;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id", nullable=false)
     *
     * @var User
     */
    private $submitter;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="ideas")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     *
     * @var Project
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="Media")
     * @ORM\JoinTable(name="ideas_medias",
     *      joinColumns={@ORM\JoinColumn(name="idea_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
     * )
     *
     * @var Collection|Media[]
     */
    private $medias;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="solution", type="text")
     *
     * @var string
     */
    private $solution;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="participate", type="boolean", nullable=false)
     *
     * @var bool
     */
    private $participate;

    /**
     * @ORM\Column(name="participate_comment", type="text", nullable=false)
     *
     * @var string
     */
    private $participateComment = "";

    /**
     * @ORM\Column(name="cost", type="bigint", options={"unsigned"=true}, nullable=true)
     *
     * @var string|null
     */
    private $cost;

    /**
     * @ORM\Column(name="status", type="integer")
     *
     * @var int
     */
    private $status = 0;

    /**
     * @ORM\Column(name="suggestion", type="string")
     *
     * @var string
     */
    private $suggestion = '';

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function getSubmitter(): UserInterface
    {
        return $this->submitter;
    }

    public function setSubmitter(UserInterface $submitter): void
    {
        $this->submitter = $submitter;
    }

    public function getCampaign(): CampaignInterface
    {
        return $this->campaign;
    }

    public function setCampaign(CampaignInterface $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function getCampaignTheme(): CampaignThemeInterface
    {
        return $this->campaignTheme;
    }

    public function setCampaignTheme(CampaignThemeInterface $campaignTheme): void
    {
        $this->campaignTheme = $campaignTheme;
    }

    public function getCampaignLocation(): ?CampaignLocationInterface
    {
        return $this->campaignLocation;
    }

    public function setCampaignLocation(?CampaignLocationInterface $campaignLocation = null): void
    {
        $this->campaignLocation = $campaignLocation;
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    public function setProject(ProjectInterface $project): void
    {
        $this->project = $project;
    }

    public function getMedias(): array
    {
        $medias = [];
        foreach ($this->medias->getValues() as $media) {
            $medias[] = $media->getId();
        }

        return $medias;
    }

    public function getMediaCollection(): Collection
    {
        return $this->medias;
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

    public function setSuggestion(string $suggestion): void
    {
        $this->suggestion = $suggestion;
    }

    public function getSuggestion(): string
    {
        return $this->suggestion;
    }

    public function setSolution(string $solution): void
    {
        $this->solution = $solution;
    }

    public function getSolution(): string
    {
        return $this->solution;
    }

    public function setParticipate(bool $participate): void
    {
        $this->participate = $participate;
    }

    public function getParticipate(): bool
    {
        return $this->participate;
    }

    public function setParticipateComment(string $participateComment): void
    {
        $this->participateComment = $participateComment;
    }

    public function getParticipateComment(): string
    {
        return $this->participateComment;
    }

    public function setCost(?string $cost = null): void
    {
        $this->cost = $cost;
    }

    public function getCost(): ?int
    {
        return $this->cost !== null ? (int) $this->cost : null;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getShortDescription(): string
    {
        $description = $this->getDescription();

        $description = strip_tags($description);

        $descriptions = explode(" ", $description);
        $descriptions = array_slice($descriptions, 0, min(22, count($descriptions) - 1));

        $description  = implode(" ", $descriptions);
        $description .= ' ...';

        return $description;
    }
}
