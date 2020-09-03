<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use App\Traits\Entity;
use App\Traits\EntityMeta;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements JsonSerializable, UserInterface
{
    use Entity;
    use EntityMeta;

    /**
     * @ORM\Column(name="firstname", type="string")
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string")
     * @var string
     */
    private $lastname;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="text", length=100)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="role", type="string")
     * @var string
     */
    private $role;

    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setRole(string $role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function generateToken(): string
    {
        $uuid4 = Uuid::uuid4();

        return $uuid4->toString();
    }
}
