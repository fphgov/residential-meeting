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
     * @ORM\ManyToOne(targetEntity="Notification")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", nullable=true)
     *
     * @var NotificationInterface
     */
    private Notification|NotificationInterface|null $notification = null;

    /**
     * @ORM\Column(name="message_id", type="string")
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

    public function getNotification(): ?NotificationInterface
    {
        return $this->notification;
    }

    public function setNotification(?NotificationInterface $notification = null): void
    {
        $this->notification = $notification;
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
