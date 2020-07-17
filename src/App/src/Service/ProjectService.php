<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Exception\ProjectNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ProjectService implements ProjectServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em                = $em;
        $this->projectRepository = $this->em->getRepository(Project::class);
    }

    public function getRepository(): EntityRepository
    {
        return $this->projectRepository;
    }
}
