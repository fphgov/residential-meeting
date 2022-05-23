<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 */
class Comment implements CommentInterface
{
    use EntityActiveTrait;
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private User $submitter;

    /**
     * @ORM\ManyToOne(targetEntity="Idea", inversedBy="comments")
     * @ORM\JoinColumn(name="idea_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private Idea $idea;

    /**
     * @ORM\Column(name="content", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="parent_comment_id", referencedColumnName="id", nullable=true)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private ?Comment $parentComment = null;

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSubmitter(): UserInterface
    {
        return $this->submitter;
    }

    public function setSubmitter(UserInterface $submitter): void
    {
        $this->submitter = $submitter;
    }

    public function getIdea(): Idea
    {
        return $this->idea;
    }

    public function setIdea(Idea $idea): void
    {
        $this->idea = $idea;
    }

    public function setParentComment(?Comment $parentComment = null): void
    {
        $this->parentComment = $parentComment;
    }

    public function getParentComment(): ?Comment
    {
        return $this->parentComment;
    }
}
