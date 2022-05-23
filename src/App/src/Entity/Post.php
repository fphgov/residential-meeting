<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore as ignore;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="posts")
 */
class Post implements PostInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @ignore
     */
    private User $author;

    /**
     * @ORM\ManyToOne(targetEntity="PostCategory")
     * @ORM\JoinColumn(name="post_category_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private PostCategory $category;

    /**
     * @ORM\ManyToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private ?Media $featuredImage;

    /**
     * @ORM\ManyToOne(targetEntity="PostStatus")
     * @ORM\JoinColumn(name="post_status_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"list", "full_detail"})
     */
    private PostStatus $status;

    /**
     * @ORM\Column(name="slug", type="string", unique=true, nullable=false)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $slug;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $title;

    /**
     * @ORM\Column(name="description", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $description;

    /**
     * @ORM\Column(name="content", type="text")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $content;

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setFeaturedImage(?Media $featuredImage = null): void
    {
        $this->featuredImage = $featuredImage;
    }

    public function getFeaturedImage(): ?Media
    {
        return $this->featuredImage;
    }

    public function setStatus(PostStatus $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function setCategory(PostCategory $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): PostCategory
    {
        return $this->category;
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

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
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
