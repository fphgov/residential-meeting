<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(name="zip_code", type="string", nullable=true)
     *
     * @Groups({"full_detail"})
     */
    private ?string $zipCode;

    /**
     * @ORM\Column(name="cluster", type="string", nullable=true)
     *
     * @Groups({"full_detail"})
     */
    private ?string $cluster;

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

    public function getCluster(): ?string
    {
        return $this->cluster;
    }

    public function setCluster(?string $cluster = null): void
    {
        $this->cluster = $cluster;
    }
}
