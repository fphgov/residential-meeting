<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Mail\MailAdapter;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

use function serialize;
use function unserialize;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MailQueueRepository")
 * @ORM\Table(name="mail_queues")
 */
class MailQueue implements JsonSerializable, MailQueueInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="mailAdapter", type="text")
     *
     * @var MailAdapter|string
     */
    private $mailAdapter;

    public function setMailAdapter(MailAdapter $mailAdapter): void
    {
        $this->mailAdapter = serialize($mailAdapter);
    }

    public function getMailAdapter(): MailAdapter
    {
        return unserialize($this->mailAdapter);
    }
}
