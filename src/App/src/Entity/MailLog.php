<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MailLogRepository")
 * @ORM\Table(name="mail_logs")
 */
class MailLog implements MailLogInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(name="messageId", type="string")
     *
     * @var string
     */
    private $messageId;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @var string
     */
    private $name;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
