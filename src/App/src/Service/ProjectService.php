<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Entity\User;
use App\Entity\WorkflowStateInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\InputFilter\InputFilterInterface;

final class ProjectService implements ProjectServiceInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(
        InputFilterInterface $inputFilter,
        EntityManagerInterface $em
    ) {
        $this->inputFilter       = $inputFilter;
        $this->em                = $em;
        $this->projectRepository = $this->em->getRepository(Project::class);
    }

    public function addProject(User $submitter, array $filteredParams): ?Project
    {
        $date  = new DateTime();

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
