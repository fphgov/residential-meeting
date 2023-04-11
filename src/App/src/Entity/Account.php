<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\Table(name="accounts")
 */
class Account implements AccountInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="auth_code", type="string")
     *
     * @Groups({"full_detail", "profile"})
     */
    private string $authCode;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=false, nullable=true)
     *
     * @Groups({"full_detail"})
     */
    private ?string $email;

    /**
     * @ORM\Column(name="voted", type="boolean", nullable=false)
     *
     * @Groups({"full_detail"})
     */
    private bool $voted = false;

    /**
     * @ORM\Column(name="privacy", type="boolean", nullable=false)
     *
     * @Groups({"full_detail"})
     */
    private bool $privacy = false;

    /**
     * @ORM\Column(name="newsletter", type="boolean", nullable=false)
     *
     * @Groups({"full_detail"})
     */
    private bool $newsletter = false;

    public function seAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function geAuthCode(): string
    {
        return $this->authCode;
    }

    public function setEmail(?string $email = null): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setVoted(bool $voted): void
    {
        $this->voted = $voted;
    }

    public function getVoted(): bool
    {
        return $this->voted;
    }

    public function setPrivacy(bool $privacy): void
    {
        $this->privacy = $privacy;
    }

    public function getPrivacy(): bool
    {
        return $this->privacy;
    }

    public function setNewsletter(bool $newsletter): void
    {
        $this->newsletter = $newsletter;
    }

    public function getNewsletter(): bool
    {
        return $this->newsletter;
    }

    public function generateToken(): string
    {
        $uuid4 = Uuid::uuid4();

        return $uuid4->toString();
    }
}
