<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 * @ORM\Table(name="links")
 */
class Link implements LinkInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Idea", inversedBy="links")
     * @ORM\JoinColumn(name="idea_id", referencedColumnName="id", nullable=false)
     */
    private Idea $idea;

    /**
     * @ORM\Column(name="href", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $href;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getIdea(): Idea
    {
        return $this->idea;
    }

    public function setIdea(Idea $idea): void
    {
        $this->idea = $idea;
    }

    public function setHref(string $href): void
    {
        $this->href = $href;
    }

    public function getHref(): string
    {
        return $this->href;
    }
}
