<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\Table(name="notifications")
 */
class Notification implements NotificationInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"list", "option", "detail", "full_detail", "vote_list"})
     */
    protected string $id;

    /** @ORM\Column(name="email", type="string", length=200, unique=true, nullable=true) */
    private string $email;

    /** @ORM\Column(name="send", type="boolean", length=200, nullable=false) */
    private bool $send = false;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setSend(bool $send): void
    {
        $this->send = $send;
    }

    public function getSend(): bool
    {
        return $this->send;
    }
}
