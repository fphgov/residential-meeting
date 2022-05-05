<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfflineVoteRepository")
 * @ORM\Table(name="votes_offline")
 */
class OfflineVote implements OfflineVoteInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=false, nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private Project $project;

    /**
     * @ORM\ManyToOne(targetEntity="VoteType")
     * @ORM\JoinColumn(name="vote_type_id", referencedColumnName="id", nullable=false)
     */
    private VoteType $voteType;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function setProject(ProjectInterface $project): void
    {
        $this->project = $project;
    }

    public function getProject(): ProjectInterface
    {
        return $this->project;
    }

    public function getVoteType(): VoteTypeInterface
    {
        return $this->voteType;
    }

    public function setVoteType(VoteTypeInterface $voteType): void
    {
        $this->voteType = $voteType;
    }
}
