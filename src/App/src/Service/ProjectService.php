<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Entity\ProjectInterface;
use App\Entity\UserInterface;
use App\Entity\WorkflowStateInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ProjectService implements ProjectServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $projectRepository;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em                = $em;
        $this->projectRepository = $this->em->getRepository(Project::class);
    }

    public function addProject(UserInterface $submitter, array $filteredParams): ?ProjectInterface
    {
        $date = new DateTime();

        $project = new Project();

        $project->setTitle($filteredParams['title']);
        $project->setDescription($filteredParams['description']);
        $project->setCost($filteredParams['cost']);
        $project->setStatus(WorkflowStateInterface::STATUS_RECEIVED);
        $project->setLocation($filteredParams['location']);
        $project->setCreatedAt($date);
        $project->setUpdatedAt($date);

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }

    public function getRepository(): EntityRepository
    {
        return $this->projectRepository;
    }
}
