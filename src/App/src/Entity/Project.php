<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use App\Traits\Entity;
use App\Traits\EntityMeta;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use JsonSerializable;

use function explode;
use function implode;
use function array_slice;
use function min;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project implements JsonSerializable, ProjectInterface
{
    use Entity;
    use EntityMeta;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     * @var Campaign
     */
    private $campaign;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignTheme")
     * @ORM\JoinColumn(name="campaign_theme_id", referencedColumnName="id")
     * @var CampaignTheme
     */
    private $campaignTheme;

    /**
     * @ORM\ManyToOne(targetEntity="CampaignLocation")
     * @ORM\JoinColumn(name="campaign_location_id", referencedColumnName="id")
     * @var CampaignLocation
     */
    private $campaignLocation;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id")
     * @var User
     */
    private $submitter;

    /**
     * @ORM\Column(name="hash_id", type="string")
     * @var string
     */
    private $hashId;

    /**
     * @ORM\Column(name="title", type="string")
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text")
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="cost", type="bigint", options={"unsigned"=true})
     * @var int
     */
    private $cost = 0;

    /**
     * @ORM\Column(name="status", type="integer")
     * @var int
     */
    private $status = 0;

    /**
     * @ORM\Column(name="published", type="boolean")
     * @var bool
     */
    private $published = 0;

    private $short_description = '';

    public function getSubmitter(): User
    {
        return $this->submitter;
    }

    public function setSubmitter(User $submitter)
    {
        $this->submitter = $submitter;
    }

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getCampaignTheme(): CampaignTheme
    {
        return $this->campaignTheme;
    }

    public function setCampaignTheme(CampaignTheme $campaignTheme)
    {
        $this->campaignTheme = $campaignTheme;
    }

    public function getHashId(): string
    {
        return $this->hashId;
    }

    public function setHashId(string $hashId)
    {
        $this->hashId = $hashId;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setCost(int $cost)
    {
        $this->cost = $cost;
    }

    public function getCost(): int
    {
        return (int)$this->cost;
    }

    public function setPublished(bool $published)
    {
        $this->published = $published;
    }

    public function getPublished(): bool
    {
        return (bool)$this->published;
    }

    public function getCampaignLocation(): CampaignLocation
    {
        return $this->campaignLocation;
    }

    public function setCampaignLocation(CampaignLocation $campaignLocation)
    {
        $this->campaignLocation = $campaignLocation;
    }

    public function setStatus(int $status)
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

        $description = implode(" ", $descriptions);
        $description = $description . ' ...';

        $this->short_description = $description;

        return $description;
    }
}
