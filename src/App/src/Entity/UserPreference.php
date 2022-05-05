<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPreferenceRepository")
 * @ORM\Table(name="user_preferences")
 */
class UserPreference implements UserPreferenceInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="userPreference")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(name="birthyear", type="smallint", nullable=true)
     */
    private ?int $birthyear;

    /**
     * @ORM\Column(name="live_in_city", type="boolean")
     */
    private bool $liveInCity = false;

    /**
     * @ORM\Column(name="postal_code", type="text", length=4, nullable=true)
     */
    private ?string $postalCode;

    /**
     * @ORM\Column(name="hear_about", type="string")
     */
    private string $hearAbout;

    /**
     * @ORM\Column(name="privacy", type="boolean")
     */
    private bool $privacy;

    /**
     * @ORM\Column(name="prize", type="boolean")
     */
    private bool $prize = false;

    /**
     * @ORM\Column(name="prizeHash", type="string", unique=true, nullable=true)
     */
    private ?string $prizeHash;

    /**
     * @ORM\Column(name="campaignEmail", type="boolean")
     */
    private bool $campaignEmail = false;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setBirthyear(?int $birthyear = null): void
    {
        $this->birthyear = $birthyear;
    }

    public function getBirthyear(): ?int
    {
        return $this->birthyear;
    }

    public function setLiveInCity(bool $liveInCity): void
    {
        $this->liveInCity = $liveInCity;
    }

    public function getLiveInCity(): bool
    {
        return $this->liveInCity;
    }

    public function setPostalCode(?string $postalCode = null): void
    {
        $this->postalCode = $postalCode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setHearAbout(string $hearAbout): void
    {
        $this->hearAbout = $hearAbout;
    }

    public function getHearAbout(): string
    {
        return $this->hearAbout;
    }

    public function setPrivacy(bool $privacy): void
    {
        $this->privacy = $privacy;
    }

    public function getPrivacy(): bool
    {
        return $this->privacy;
    }

    public function setPrize(bool $prize): void
    {
        $this->prize = $prize;
    }

    public function getPrize(): bool
    {
        return $this->prize;
    }

    public function setPrizeHash(?string $prizeHash = null): void
    {
        $this->prizeHash = $prizeHash;
    }

    public function getPrizeHash(): ?string
    {
        return $this->prizeHash;
    }

    public function setCampaignEmail(bool $campaignEmail): void
    {
        $this->campaignEmail = $campaignEmail;
    }

    public function getCampaignEmail(): bool
    {
        return $this->campaignEmail;
    }
}
