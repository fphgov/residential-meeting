<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Mail\MailAdapterInterface;

use function serialize;
use function unserialize;
use function stream_get_contents;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MailQueueRepository")
 * @ORM\Table(name="mail_queues")
 */
class MailQueue implements MailQueueInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private User $user;

    /**
     * @ORM\Column(name="mail_adapter", type="blob")
     * @var resource|null
     */
    private $mailAdapter;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function setMailAdapter(MailAdapterInterface $mailAdapter): void
    {
        $this->mailAdapter = serialize($mailAdapter);
    }

    public function getMailAdapter(): MailAdapterInterface
    {
        $mailAdapterContent = stream_get_contents($this->mailAdapter);

        return unserialize((string)$mailAdapterContent);
    }
}
