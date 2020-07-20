<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use App\Traits\Entity;
use App\Traits\EntityMeta;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use JsonSerializable;

use function substr;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project implements JsonSerializable
{
    const DISABLE_SHOW_DEFAULT = [
        'id',
        'updatedAt',
    ];

    use Entity;
    use EntityMeta;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=true)
     * @var Campaign
     */
    private $campaign;

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
     * @ORM\Column(name="status", type="string")
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(name="location", type="string")
     * @var string
     */
    private $location;

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
    
    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign = null)
    {
        $this->campaign = $campaign;
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
        return $this->published;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
    
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getShortDescription(): string
    {
        $description = $this->getDescription();
        $description = strip_tags(substr($description, 0, 60));

        $this->short_description = $description;

        return $description;
    }
}
