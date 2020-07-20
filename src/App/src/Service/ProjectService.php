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

    public function addProject(User $submitter, array $filteredParams)
    {
        $uuid4 = Uuid::uuid4();

        $project = new Project();

        $project->setCampaign(null);
        $project->setSubmitter($submitter);
        $project->setHashId($uuid4->toString());
        $project->setTitle($filteredParams['title']);
        $project->setDescription($filteredParams['description']);
        $project->setCost($filteredParams['cost']);
        $project->setStatus('');
        $project->setLocation('');
        $project->setCreatedAt(new \DateTime());
        $project->setUpdatedAt(new \DateTime());

        $this->em->persist($project);

        $this->em->flush();
    }

    public function getRepository(): EntityRepository
    {
        return $this->projectRepository;
    }
}
