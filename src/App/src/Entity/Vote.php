<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ORM\Table(name="votes")
 */
class Vote implements JsonSerializable, VoteInterface
{
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="vote")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true, nullable=false)
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="care_id", referencedColumnName="id", nullable=false)
     *
     * @var Project
     */
    private $projectCare;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="green_id", referencedColumnName="id", nullable=false)
     *
     * @var Project
     */
    private $projectGreen;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="whole_id", referencedColumnName="id", nullable=false)
     *
     * @var Project
     */
    private $projectWhole;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function setProjectCare(ProjectInterface $projectCare): void
    {
        $this->projectCare = $projectCare;
    }

    public function getProjectCare(): ProjectInterface
    {
        return $this->projectCare;
    }

    public function setProjectGreen(ProjectInterface $projectGreen): void
    {
        $this->projectGreen = $projectGreen;
    }

    public function getProjectGreen(): ProjectInterface
    {
        return $this->projectGreen;
    }

    public function setProjectWhole(ProjectInterface $projectWhole): void
    {
        $this->projectWhole = $projectWhole;
    }

    public function getProjectWhole(): ProjectInterface
    {
        return $this->projectWhole;
    }
}
