<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Project;
use App\Exception\ProjectNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\InputFilter\InputFilterInterface;
use Ramsey\Uuid\Uuid;

final class ProjectService implements ProjectServiceInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
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
        $uuid4 = Uuid::uuid4();
        $date  = new \DateTime();

        $project = new Project();

        $project->setCampaign(null);
        $project->setSubmitter($submitter);
        $project->setHashId($uuid4->toString());
        $project->setTitle($filteredParams['title']);
        $project->setDescription($filteredParams['description']);
        $project->setCost($filteredParams['cost']);
        $project->setStatus(Project::STATUS_RECEIVED);
        $project->setLocation($filteredParams['location']);
        $project->setPublished(false);
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
