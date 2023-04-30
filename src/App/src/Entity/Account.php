<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\Table(name="accounts", indexes={@ORM\Index(name="account_idx", columns={"auth_code"})})
 */
class Account implements AccountInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"list", "option", "detail", "full_detail", "vote_list"})
     */
    protected int $id;

    /**
     * @ORM\Column(name="auth_code", type="string", length=14)
     *
     * @Groups({"full_detail", "profile"})
     */
    private string $authCode;

    /**
     * @ORM\Column(name="zip_code", type="string", length=4, nullable=true)
     *
     * @Groups({"full_detail"})
     */
    private ?string $zipCode = null;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function seAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function geAuthCode(): string
    {
        return $this->authCode;
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

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode = null): void
    {
        $this->zipCode = $zipCode;
    }
}
