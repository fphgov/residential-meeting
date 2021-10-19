<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampaignRepository")
 * @ORM\Table(name="campaigns")
 */
class Campaign implements JsonSerializable, CampaignInterface
{
    use EntityActiveTrait;
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\OneToMany(targetEntity="Idea", mappedBy="campaign")
     *
     * @var Collection|Idea[]
     */
    private $ideas;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="short_title", type="string")
     *
     * @var string
     */
    private $shortTitle;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @var string
     */
    private $description;

    public function getIdeas(): array
    {
        $ideas = [];
        foreach ($this->ideas->getValues() as $idea) {
            $ideas[] = $idea->getId();
        }

        return $ideas;
    }

    public function getIdeaCollection(): Collection
    {
        return $this->ideas;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setShortTitle(string $shortTitle): void
    {
        $this->shortTitle = $shortTitle;
    }

    public function getShortTitle(): string
    {
        return $this->shortTitle;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
