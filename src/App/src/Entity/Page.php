<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(name="pages")
 */
class Page implements PageInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="status", type="string")
     *
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(name="slug", type="string", unique=true)
     *
     * @Groups({"list", "detail", "full_detail"})
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="content", type="text")
     *
     * @Groups({"detail", "full_detail"})
     * @var string
     */
    private $content;

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
