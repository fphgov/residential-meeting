<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ORM\Table(name="votes")
 */
class Vote implements VoteInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="votes", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=false, nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="VoteType", cascade={"persist"})
     * @ORM\JoinColumn(name="vote_type_id", referencedColumnName="id", nullable=true)
     */
    private ?VoteType $voteType;

    /**
     * @ORM\ManyToOne(targetEntity="Project", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private Project $project;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getVoteType(): VoteTypeInterface
    {
        return $this->voteType;
    }

    public function setVoteType(VoteTypeInterface $voteType): void
    {
        $this->voteType = $voteType;
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    public function setProject(ProjectInterface $project): void
    {
        $this->project = $project;
    }
}
