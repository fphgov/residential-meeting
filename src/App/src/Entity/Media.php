<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @ORM\Table(name="medias")
 */
class Media implements MediaInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Groups({"list", "detail"})
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(name="filename", type="string")
     * @Groups({"list", "detail"})
     *
     * @var string
     */
    private $filename;

    /**
     * @ORM\Column(name="type", type="string", nullable=true)
     * @Groups({"list", "detail"})
     *
     * @var string|null
     */
    private $type;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @var DateTime
     */
    protected $updatedAt;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setType(?string $type = null): void
    {
        $this->type = $type;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
