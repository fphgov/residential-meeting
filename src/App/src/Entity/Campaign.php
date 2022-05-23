<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CampaignRepository")
 * @ORM\Table(name="campaigns")
 */
class Campaign implements CampaignInterface
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
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $title;

    /**
     * @ORM\Column(name="short_title", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $shortTitle;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $description;

    public function __construct()
    {
        $this->ideas = new ArrayCollection();
    }

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
