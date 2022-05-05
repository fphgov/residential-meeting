<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImplementationRepository")
 * @ORM\Table(name="implementations")
 */
class Implementation implements ImplementationInterface
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="comments")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private Project $project;

    /**
     * @ORM\ManyToMany(targetEntity="Media")
     * @ORM\JoinTable(name="implementations_medias",
     *      joinColumns={@ORM\JoinColumn(name="implementation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="media_id", referencedColumnName="id")}
     * )
     *
     * @Groups({"detail", "full_detail"})
     * @var Collection|Media[]
     */
    private Collection $medias;

    /**
     * @ORM\Column(name="content", type="text")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $content;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

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

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getMedias(): array
    {
        $medias = [];
        foreach ($this->medias->getValues() as $media) {
            $medias[] = [
                'id'   => $media->getId(),
                'type' => $media->getType(),
            ];
        }

        return $medias;
    }

    public function getMediaCollection(): Collection
    {
        return $this->medias;
    }

    public function addMedia(MediaInterface $media): self
    {
        if (! $this->medias->contains($media)) {
            $this->medias[] = $media;
        }

        return $this;
    }

    public function removeMedia(MediaInterface $media): self
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
        }

        return $this;
    }
}
