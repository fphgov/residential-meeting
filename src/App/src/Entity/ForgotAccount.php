<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForgotAccountRepository")
 * @ORM\Table(name="forgot_account")
 */
class ForgotAccount implements ForgotAccountInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="token", type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @Groups({"full_detail"})
     */
    private UuidInterface $token;

    /**
     * @ORM\Column(name="email", type="string", length=200, unique=false, nullable=true)
     *
     * @Groups({"full_detail"})
     */
    private string $email;

    /**
     * @ORM\Column(name="expiration", type="datetime", nullable=false)
     *
     * @Groups({"full_detail"})
     */
    private DateTime $expirationDate;

    public function getToken(): UuidInterface
    {
        return $this->token;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }
}
