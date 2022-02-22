<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CampaignTheme;
use App\Entity\CampaignLocation;
use App\Entity\Project;
use App\Entity\Media;
use App\Entity\ProjectInterface;
use App\Entity\PhaseInterface;
use App\Entity\UserInterface;
use App\Entity\Implementation;
use App\Entity\WorkflowState;
use App\Entity\WorkflowStateInterface;
use App\Interfaces\EntityInterface;
use App\Exception\NoHasPhaseCategoryException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\UploadedFileInterface;

use function basename;
use function is_countable;
use function is_numberic;
use function parse_str;
use function str_replace;

final class ProjectService implements ProjectServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $projectRepository;

    /** @var EntityRepository */
    private $campaignThemeRepository;

    /** @var EntityRepository */
    private $workflowStateRepository;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em                      = $em;
        $this->campaignThemeRepository = $this->em->getRepository(CampaignTheme::class);
        $this->projectRepository       = $this->em->getRepository(Project::class);
        $this->workflowStateRepository = $this->em->getRepository(WorkflowState::class);
    }

    public function addProject(
        UserInterface $submitter,
        array $filteredParams
    ): ?ProjectInterface {
        $date = new DateTime();

        $project = new Project();

        $theme = $this->campaignThemeRepository->findOneBy([
            'id' => $filteredParams['theme'],
        ]);

        if (! $theme instanceof CampaignTheme) {
            throw new NoHasPhaseCategoryException($filteredParams['theme']);
        }

        $project->setSubmitter($submitter);
        $project->setTitle($filteredParams['title']);
        $project->setDescription($filteredParams['description']);
        $project->setSolution($filteredParams['solution']);
        $project->setCost($filteredParams['cost']);
        $project->setWorkflowState(
            $this->em->getReference(WorkflowState::class, WorkflowStateInterface::STATUS_RECEIVED)
        );
        $project->setCampaignTheme($theme);
        $project->setCreatedAt($date);
        $project->setUpdatedAt($date);

        if (isset($filteredParams['location']) && ! empty($filteredParams['location'])) {
            parse_str($filteredParams['location'], $suggestion);

            if (isset($suggestion['geometry']) && ! empty($suggestion['geometry'])) {
                parse_str($suggestion['geometry'], $geometry);

                if (isset($suggestion['nfn'])) {
                    $nfn = str_replace('.', '', $suggestion['nfn']);

                    $location = $this->campaignLocationRepository->findOneBy([
                        'code'     => "AREA" . $nfn,
                        'campaign' => $theme->getCampaign(),
                    ]);

                    if ($location instanceof CampaignLocation) {
                        $project->addCampaignLocation($location);
                    }
                }

                $project->setLatitude((float) $geometry['y']);
                $project->setLongitude((float) $geometry['x']);
            }
        }

        if (isset($filteredParams['medias']) && is_countable($filteredParams['medias'])) {
            $this->addAttachments($project, $filteredParams['medias'], $date);
        }

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }

    public function modifyProject(
        UserInterface $submitter,
        ProjectInterface $project,
        array $filteredParams
    ): void {
        $date = new DateTime();

        $theme = $this->campaignThemeRepository->findOneBy([
            'id' => $filteredParams['theme'],
        ]);

        $project->setCampaignTheme($theme);
        $project->setTitle($filteredParams['title']);
        $project->setSolution($filteredParams['solution']);
        $project->setDescription($filteredParams['description']);
        $project->setCost(is_numeric($filteredParams['cost']) ? $filteredParams['cost'] : null);

        $workflowState = $this->workflowStateRepository->findOneBy([
            'code' => $filteredParams['workflowState'],
        ]);

        if ($workflowState) {
            $project->setWorkflowState($workflowState);
        }

        if (isset($filteredParams['medias']) && is_countable($filteredParams['medias'])) {
            $this->addAttachments($project, $filteredParams['medias'], $date);
        }

        if (isset($filteredParams['location']) && ! empty($filteredParams['location'])) {
            parse_str($filteredParams['location'], $suggestion);

            if (isset($suggestion['geometry']) && ! empty($suggestion['geometry'])) {
                parse_str($suggestion['geometry'], $geometry);

                if (isset($suggestion['nfn'])) {
                    $nfn = str_replace('.', '', $suggestion['nfn']);

                    $location = $this->campaignLocationRepository->findOneBy([
                        'code'     => "AREA" . $nfn,
                        'campaign' => $theme->getCampaign(),
                    ]);

                    if ($location instanceof CampaignLocation) {
                        $project->addCampaignLocation($location);
                    }
                }

                $project->setLatitude((float) $geometry['y']);
                $project->setLongitude((float) $geometry['x']);
            }
        }

        if (isset($filteredParams['implementation']) && ! empty($filteredParams['implementation'])) {
            $implementation = new Implementation();
            $implementation->setSubmitter($submitter);
            $implementation->setProject($project);
            $implementation->setContent($filteredParams['implementation']);
            $implementation->setActive(true);
            $implementation->setCreatedAt($date);
            $implementation->setUpdatedAt($date);

            if (isset($filteredParams['implementationMedia']) && ! empty($filteredParams['implementationMedia'])) {
                $this->addAttachments($implementation, $filteredParams['implementationMedia'], $date);
            }

            $project->addImplementation($implementation);

            $this->em->persist($implementation);
        }

        $project->setUpdatedAt($date);

        $this->em->flush();
    }

    private function addAttachments(EntityInterface $entity, array $files, DateTime $date): void
    {
        foreach ($files as $file) {
            if (! $file instanceof UploadedFileInterface) {
                continue;
            }

            $filename = basename($file->getStream()->getMetaData('uri'));

            $media = new Media();
            $media->setFilename($filename);
            $media->setType($file->getClientMediaType());
            $media->setCreatedAt($date);
            $media->setUpdatedAt($date);

            $this->em->persist($media);

            $entity->addMedia($media);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->projectRepository;
    }
}
