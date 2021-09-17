<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\CampaignTheme;
use App\Entity\Idea;
use App\Entity\IdeaInterface;
use App\Entity\Media;
use App\Entity\Link;
use App\Entity\PhaseInterface;
use App\Entity\UserInterface;
use App\Entity\WorkflowState;
use App\Entity\WorkflowStateInterface;
use App\Exception\NoHasPhaseCategoryException;
use App\Service\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\UploadedFileInterface;

use function basename;
use function is_countable;
use function is_array;

final class IdeaService implements IdeaServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $ideaRepository;

    /** @var EntityRepository */
    private $campaignThemeRepository;

    /** @var PhaseServiceInterface */
    private $phaseService;

    public function __construct(
        EntityManagerInterface $em,
        PhaseServiceInterface $phaseService
    ) {
        $this->em                      = $em;
        $this->ideaRepository          = $this->em->getRepository(Idea::class);
        $this->campaignThemeRepository = $this->em->getRepository(CampaignTheme::class);
        $this->phaseService            = $phaseService;
    }

    public function addIdea(
        UserInterface $submitter,
        array $filteredParams
    ): ?IdeaInterface {
        $phase = $this->phaseService->phaseCheck(PhaseInterface::PHASE_IDEATION);

        $date = new DateTime();

        $idea = new Idea();

        $category = $this->campaignThemeRepository->findOneBy([
            'campaign' => $phase->getCampaign(),
            'code'     => $filteredParams['category'],
        ]);

        if (! $category instanceof CampaignTheme) {
            throw new NoHasPhaseCategoryException($filteredParams['category']);
        }

        $idea->setSubmitter($submitter);
        $idea->setTitle($filteredParams['title']);
        $idea->setDescription($filteredParams['description']);
        $idea->setSolution($filteredParams['solution']);
        $idea->setCost($filteredParams['cost']);
        $idea->setParticipate($filteredParams['participate']);
        $idea->setParticipateComment($filteredParams['participate_comment']);
        $idea->setCampaign($phase->getCampaign());
        $idea->setCampaignTheme($category);
        $idea->setWorkflowState(
            $this->em->getReference(WorkflowState::class, WorkflowStateInterface::STATUS_RECEIVED)
        );
        $idea->setSuggestion($filteredParams['suggestion'] ?? '');

        if (is_array($filteredParams['file'])) {
            $this->addAttachments($idea, $filteredParams['file'], $date);
        }

        if (isset($filteredParams['links']) && is_countable($filteredParams['links'])) {
            foreach ($filteredParams['links'] as $filteredLink) {
                $link = new Link();
                $link->setIdea($idea);
                $link->setHref($filteredLink);

                $idea->addLink($link);

                $this->em->persist($link);
            }
        }

        $idea->setCreatedAt($date);
        $idea->setUpdatedAt($date);

        $this->em->persist($idea);
        $this->em->flush();

        return $idea;
    }

    public function getRepository(): EntityRepository
    {
        return $this->ideaRepository;
    }

    private function addAttachments(IdeaInterface $idea, array $files, DateTime $date): void
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

            $idea->addMedia($media);
        }
    }
}
