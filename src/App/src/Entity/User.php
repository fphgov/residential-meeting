<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements JsonSerializable, UserInterface
{
    use EntityActiveTrait;
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\OneToOne(targetEntity="UserPreference", mappedBy="user")
     *
     * @var UserPreference
     */
    private $userPreference;

    /**
     * @ORM\OneToOne(targetEntity="Vote", mappedBy="user")
     *
     * @var Vote
     */
    private $vote;

    /**
     * @ORM\Column(name="lutece_id", type="string", nullable=true)
     *
     * @var string
     */
    private $luteceId;

    /**
     * @ORM\Column(name="firstname", type="string")
     *
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string")
     *
     * @var string
     */
    private $lastname;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="text", length=100)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="role", type="string")
     *
     * @var string
     */
    private $role = 'user';

    /**
     * @ORM\Column(name="hash", type="string", unique=true, nullable=true)
     *
     * @var string|null
     */
    private $hash;

    public function setUserPreference(UserPreference $userPreference)
    {
        $this->userPreference = $userPreference;
    }

    public function getUserPreference(): UserPreference
    {
        return $this->userPreference;
    }

    public function setVote(?Vote $vote = null)
    {
        $this->vote = $vote;
    }

    public function getVote(): ?Vote
    {
        return $this->vote;
    }

    public function setLuteceId(string $luteceId): void
    {
        $this->luteceId = $luteceId;
    }

    public function getLuteceId(): string
    {
        return $this->luteceId;
    }

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

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setHash(?string $hash = null): void
    {
        $this->hash = $hash;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function generateToken(): string
    {
        $uuid4 = Uuid::uuid4();

        return $uuid4->toString();
    }
}
