<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Mail\Entity\MailInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MailRepository")
 * @ORM\Table(name="mails")
 */
class Mail implements MailInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="code", type="string")
     *
     * @Groups({"list", "option", "detail", "full_detail"})
     *
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @Groups({"list", "option", "detail", "full_detail"})
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="plain_text", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     *
     * @var string
     */
    private $plainText;

    /**
     * @ORM\Column(name="html", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     *
     * @var string
     */
    private $html;

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPlainText(string $plainText): void
    {
        $this->plainText = $plainText;
    }

    public function getPlainText(): string
    {
        return $this->plainText;
    }

    public function setHtml(string $html): void
    {
        $this->html = $html;
    }

    public function getHtml(): string
    {
        return $this->html;
    }
}
