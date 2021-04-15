<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPreferenceRepository")
 * @ORM\Table(name="user_preferences")
 */
class UserPreference implements JsonSerializable, UserPreferenceInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="userPreference")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(name="address", type="string")
     *
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(name="birthyear", type="smallint", nullable=true)
     *
     * @var int
     */
    private $birthyear;

    /**
     * @ORM\Column(name="live_in_city", type="boolean")
     *
     * @var bool
     */
    private $liveInCity;

    /**
     * @ORM\Column(name="postal_code", type="text", length=4, nullable=true)
     *
     * @var string
     */
    private $postalCode;

    /**
     * @ORM\Column(name="nickname", type="string")
     *
     * @var string
     */
    private $nickname;

    /**
     * @ORM\Column(name="created", type="date")
     *
     * @var string
     */
    private $created;

    /**
     * @ORM\Column(name="policy", type="boolean")
     *
     * @var bool
     */
    private $policy;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): string
    {
        return $this->address;
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

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setpolicy(bool $policy): void
    {
        $this->policy = $policy;
    }

    public function getpolicy(): bool
    {
        return $this->policy;
    }

    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }
}
