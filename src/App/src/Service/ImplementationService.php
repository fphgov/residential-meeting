<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Entity\Media;
use App\Entity\Implementation;
use App\Entity\ImplementationCategory;
use App\Entity\ImplementationInterface;
use App\Entity\ImplementationStatus;
use App\Entity\UserInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Laminas\Diactoros\UploadedFile;
use Laminas\Log\Logger;

use function basename;

final class ImplementationService implements ImplementationServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $implementationRepository;

    /** @var EntityRepository */
    private $projectRepository;

    /** @var Logger */
    private $audit;

    public function __construct(
        EntityManagerInterface $em,
        Logger $audit
    ) {
        $this->em                       = $em;
        $this->implementationRepository = $this->em->getRepository(Implementation::class);
        $this->projectRepository        = $this->em->getRepository(Project::class);
        $this->audit                    = $audit;
    }

    public function addImplementation(
        UserInterface $submitter,
        array $filteredParams
    ): void {
        $date = new DateTime();

        $project = $this->projectRepository->find($filteredParams['project']);

        $implementation = new Implementation();

        $implementation->setSubmitter($submitter);
        $implementation->setProject($project);

        $implementation->setContent($filteredParams['content']);

        if (isset($filteredParams['medias'])) {
            $this->addMedias($implementation, $filteredParams['medias'], $date);
        }

        $implementation->setCreatedAt($date);
        $implementation->setUpdatedAt($date);

        $this->em->persist($implementation);
        $this->em->flush();
    }

    public function modifyImplementation(
        ImplementationInterface $implementation,
        array $filteredParams
    ): void {
        $date = new DateTime();

        $implementation->setContent($filteredParams['content']);

        if (isset($filteredParams['medias'])) {
            $this->addMedias($implementation, $filteredParams['medias'], $date);
        }

        $implementation->setUpdatedAt($date);

        $this->em->flush();
    }

    public function deleteImplementation(
        UserInterface $submitter,
        ImplementationInterface $implementation
    ): void {
        $implementationId = $implementation->getId();

        try {
            $this->em->remove($implementation);

            foreach ($implementation->getMediaCollection() as $media) {
                $this->em->remove($media);
            }

            $this->em->flush();

            $this->audit->err('Success deleted implementation', [
                'extra' => 'Implementation ID: ' . $implementationId . ' deleted by ' . $submitter->getId(),
            ]);
        } catch (Exception $e) {
            $this->audit->err('Failed delete implementation from database', [
                'extra' => $e->getMessage(),
            ]);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->implementationRepository;
    }

    private function addMedias(ImplementationInterface $implementation, array $medias, DateTime $date): void
    {
        foreach ($medias as $media) {
            if ($media instanceof UploadedFile) {
                $this->addAttachment($implementation, $media, $date);
            }
        }
    }

    private function addAttachment(ImplementationInterface $implementation, UploadedFile $file, DateTime $date): void
    {
        $filename = basename($file->getStream()->getMetaData('uri'));

        $media = new Media();
        $media->setFilename($filename);
        $media->setType($file->getClientMediaType());
        $media->setCreatedAt($date);
        $media->setUpdatedAt($date);

        $this->em->persist($media);

        $implementation->addMedia($media);
    }
}
