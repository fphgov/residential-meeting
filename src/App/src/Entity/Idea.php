<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
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
     * @ORM\ManyToOne(targetEntity="CampaignTheme")
     * @ORM\JoinColumn(name="campaign_theme_id", referencedColumnName="id")
     *
     * @var CampaignTheme
     */
    private $campaignTheme;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignLocation")
     * @ORM\JoinColumn(name="campaign_location_id", referencedColumnName="id")
     *
     * @var CampaignLocation
     */
    private $campaignLocation;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id")
     *
     * @var User
     */
    private $submitter;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="ideas")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

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
     * @ORM\Column(name="comment", type="text")
     *
     * @var string
     */
    private $comment;

    /**
     * @ORM\Column(name="cost", type="bigint", options={"unsigned"=true})
     *
     * @var int
     */
    private $cost = 0;

    /**
     * @ORM\Column(name="status", type="integer")
     *
     * @var int
     */
    private $status = 0;

    /**
     * @ORM\Column(name="attachment", type="string")
     *
     * @var string
     */
    private $attachment = '';

    public function getSubmitter(): User
    {
        return $this->submitter;
    }

    public function setSubmitter(User $submitter): void
    {
        $this->submitter = $submitter;
    }

    public function getCampaignTheme(): CampaignTheme
    {
        return $this->campaignTheme;
    }

    public function setCampaignTheme(CampaignTheme $campaignTheme): void
    {
        $this->campaignTheme = $campaignTheme;
    }

    public function getCampaignLocation(): CampaignLocation
    {
        return $this->campaignLocation;
    }

    public function setCampaignLocation(CampaignLocation $campaignLocation)
    {
        $this->campaignLocation = $campaignLocation;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
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

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    public function getCost(): int
    {
        return (int) $this->cost;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setAttachment(string $attachment): void
    {
        $this->attachment = $attachment;
    }

    public function getAttachment(): string
    {
        return $this->attachment;
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
