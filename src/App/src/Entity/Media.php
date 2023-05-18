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
     *
     * @Groups({"list", "detail", "full_detail"})
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(name="filename", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $filename;

    /**
     * @ORM\Column(name="type", type="string", nullable=true)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private ?string $type;

    /**
     * @ORM\Column(name="expiration", type="datetime", nullable=true)
     *
     * @Groups({"full_detail"})
     */
    private ?DateTime $expirationDate = null;

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

    public function getExpirationDate(): ?DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?DateTime $expirationDate = null): void
    {
        $this->expirationDate = $expirationDate;
    }
}
