<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfflineVoteRepository")
 * @ORM\Table(name="votes_offline")
 */
class OfflineVote implements JsonSerializable, OfflineVoteInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=false)
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="care_id", referencedColumnName="id")
     *
     * @var Project
     */
    private $projectCare;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="green_id", referencedColumnName="id")
     *
     * @var Project
     */
    private $projectGreen;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="whole_id", referencedColumnName="id")
     *
     * @var Project
     */
    private $projectWhole;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setProjectCare(Project $projectCare): void
    {
        $this->projectCare = $projectCare;
    }

    public function getProjectCare(): Project
    {
        return $this->projectCare;
    }

    public function setProjectGreen(Project $projectGreen): void
    {
        $this->projectGreen = $projectGreen;
    }

    public function getProjectGreen(): Project
    {
        return $this->projectGreen;
    }

    public function setProjectWhole(Project $projectWhole): void
    {
        $this->projectWhole = $projectWhole;
    }

    public function getProjectWhole(): Project
    {
        return $this->projectWhole;
    }
}
