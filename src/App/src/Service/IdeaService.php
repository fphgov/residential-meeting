<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\CampaignTheme;
use App\Entity\Idea;
use App\Entity\IdeaInterface;
use App\Entity\UserInterface;
use App\Entity\WorkflowStateInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class IdeaService implements IdeaServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $ideaRepository;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em             = $em;
        $this->ideaRepository = $this->em->getRepository(Idea::class);
    }

    public function addIdea(
        UserInterface $submitter,
        array $filteredParams
    ): ?IdeaInterface {
        $date = new DateTime();

        $idea = new Idea();

        $idea->setSubmitter($submitter);
        $idea->setTitle($filteredParams['title']);
        $idea->setDescription($filteredParams['description']);
        $idea->setSolution($filteredParams['solution']);
        $idea->setCost($filteredParams['cost']);
        $idea->setParticipate($filteredParams['participate']);
        $idea->setParticipateComment($filteredParams['participate_comment']);
        $idea->setCampaign(
            $this->em->getReference(Campaign::class, 2)
        );
        $idea->setCampaignTheme(
            $this->em->getReference(CampaignTheme::class, $filteredParams['category'])
        );
        $idea->setStatus(WorkflowStateInterface::STATUS_RECEIVED);
        $idea->setSuggestion($filteredParams['suggestion'] ?? '');
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
}
