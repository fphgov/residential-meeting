<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsletterRepository")
 * @ORM\Table(name="newsletters")
 */
class Newsletter implements NewsletterInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="firstname", type="string")
     *
     * @Groups({"list", "detail", "full_detail", "profile"})
     */
    private string $firstname;

    /**
     * @ORM\Column(name="lastname", type="string")
     *
     * @Groups({"list", "detail", "full_detail", "profile"})
     */
    private string $lastname;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     *
     * @Groups({"full_detail", "profile"})
     */
    private string $email;

    /**
     * @ORM\Column(name="sync", type="boolean", nullable=false)
     *
     * @Groups({"full_detail"})
     */
    private bool $sync = false;

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setSync(bool $sync): void
    {
        $this->sync = $sync;
    }

    public function getSync(): bool
    {
        return $this->sync;
    }
}
