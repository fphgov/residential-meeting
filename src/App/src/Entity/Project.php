<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use App\Traits\Entity;
use App\Traits\EntityMeta;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
final class Project implements JsonSerializable
{
    const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    use Entity;
    use EntityMeta;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id")
     * @var User
     */
    private $submitter;

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

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setCost(int $cost)
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setPublished(bool $published)
    {
        $this->published = $published;

        return $this;
    }

    public function getPublished(): bool
    {
        return $this->published;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
    
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
