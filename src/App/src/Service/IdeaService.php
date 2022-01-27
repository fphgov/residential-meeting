<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CampaignLocation;
use App\Entity\CampaignTheme;
use App\Entity\Idea;
use App\Entity\IdeaInterface;
use App\Entity\Link;
use App\Entity\Media;
use App\Entity\PhaseInterface;
use App\Entity\UserInterface;
use App\Entity\WorkflowState;
use App\Entity\WorkflowStateExtra;
use App\Entity\WorkflowStateInterface;
use App\Exception\NoHasPhaseCategoryException;
use App\Exception\NotPossibleSubmitIdeaWithAdminAccountException;
use App\Helper\MailContentHelper;
use App\Service\MailQueueServiceInterface;
use App\Service\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\Log\Logger;
use Mail\MailAdapter;
use Psr\Http\Message\UploadedFileInterface;
use Throwable;

use function basename;
use function error_log;
use function in_array;
use function is_array;
use function is_countable;
use function is_numeric;
use function parse_str;
use function str_replace;
use function wordwrap;

final class IdeaService implements IdeaServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $ideaRepository;

    /** @var EntityRepository */
    private $campaignThemeRepository;

    /** @var EntityRepository */
    private $campaignLocationRepository;

    /** @var EntityRepository */
    private $workflowStateRepository;

    /** @var EntityRepository */
    private $workflowStateExtraRepository;

    /** @var PhaseServiceInterface */
    private $phaseService;

    /** @var Logger */
    private $audit;

    /** @var MailAdapter */
    private $mailAdapter;

    /** @var MailContentHelper */
    private $mailContentHelper;

    /** @var MailQueueServiceInterface */
    private $mailQueueService;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        PhaseServiceInterface $phaseService,
        Logger $audit,
        MailAdapter $mailAdapter,
        MailContentHelper $mailContentHelper,
        MailQueueServiceInterface $mailQueueService
    ) {
        $this->config                       = $config;
        $this->em                           = $em;
        $this->ideaRepository               = $this->em->getRepository(Idea::class);
        $this->campaignThemeRepository      = $this->em->getRepository(CampaignTheme::class);
        $this->campaignLocationRepository   = $this->em->getRepository(CampaignLocation::class);
        $this->workflowStateRepository      = $this->em->getRepository(WorkflowState::class);
        $this->workflowStateExtraRepository = $this->em->getRepository(WorkflowStateExtra::class);
        $this->phaseService                 = $phaseService;
        $this->audit                        = $audit;
        $this->mailAdapter                  = $mailAdapter;
        $this->mailContentHelper            = $mailContentHelper;
        $this->mailQueueService             = $mailQueueService;
    }

    public function addIdea(
        UserInterface $user,
        array $filteredParams
    ): ?IdeaInterface {
        $phase = $this->phaseService->phaseCheck(PhaseInterface::PHASE_IDEATION);

        if (in_array($user->getRole(), ['developer', 'admin'], true)) {
            throw new NotPossibleSubmitIdeaWithAdminAccountException($user->getRole());
        }

        $date = new DateTime();

        $idea = new Idea();

        $theme = $this->campaignThemeRepository->findOneBy([
            'campaign' => $phase->getCampaign(),
            'id'       => $filteredParams['theme'],
        ]);

        if (! $theme instanceof CampaignTheme) {
            throw new NoHasPhaseCategoryException($filteredParams['theme']);
        }

        $idea->setSubmitter($user);
        $idea->setTitle($filteredParams['title']);
        $idea->setDescription($filteredParams['description']);
        $idea->setSolution($filteredParams['solution']);
        $idea->setCost($filteredParams['cost']);
        $idea->setParticipate($filteredParams['participate']);
        $idea->setParticipateComment($filteredParams['participate_comment'] ? $filteredParams['participate_comment'] : '');
        $idea->setLocationDescription($filteredParams['location_description'] ? $filteredParams['location_description'] : '');
        $idea->setCampaign($phase->getCampaign());
        $idea->setCampaignTheme($theme);
        $idea->setWorkflowState(
            $this->em->getReference(WorkflowState::class, WorkflowStateInterface::STATUS_RECEIVED)
        );

        if (isset($filteredParams['location']) && ! empty($filteredParams['location'])) {
            parse_str($filteredParams['location'], $suggestion);

            if (isset($suggestion['geometry']) && ! empty($suggestion['geometry'])) {
                parse_str($suggestion['geometry'], $geometry);

                if (isset($suggestion['nfn'])) {
                    $nfn = str_replace('.', '', $suggestion['nfn']);

                    $location = $this->campaignLocationRepository->findOneBy([
                        'code'     => "AREA" . $nfn,
                        'campaign' => $phase->getCampaign(),
                    ]);

                    if ($location instanceof CampaignLocation) {
                        $idea->setCampaignLocation($location);
                    }
                }

                $idea->setLatitude((float) $geometry['y']);
                $idea->setLongitude((float) $geometry['x']);
            }
        }

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

        $this->sendIdeaConfirmationEmail($user, $idea);

        return $idea;
    }

    public function modifyIdea(
        IdeaInterface $idea,
        array $filteredParams
    ): void {
        $date = new DateTime();

        if (isset($filteredParams['title'])) {
            $idea->setTitle($filteredParams['title']);
        }

        if (isset($filteredParams['solution'])) {
            $idea->setSolution($filteredParams['solution']);
        }

        if (isset($filteredParams['description'])) {
            $idea->setDescription($filteredParams['description']);
        }

        if (isset($filteredParams['cost'])) {
            $idea->setCost(is_numeric($filteredParams['cost']) ? $filteredParams['cost'] : null);
        }

        if (isset($filteredParams['location_description'])) {
            $idea->setLocationDescription($filteredParams['location_description'] ? $filteredParams['location_description'] : '');
        }

        if (isset($filteredParams['answer'])) {
            $idea->setAnswer($filteredParams['answer']);
        }

        if (isset($filteredParams['workflowState'])) {
            $workflowState = $this->workflowStateRepository->findOneBy([
                'code' => $filteredParams['workflowState'],
            ]);

            if ($workflowState) {
                $idea->setWorkflowState($workflowState);
            }
        }

        if (isset($filteredParams['workflowStateExtra']) && $filteredParams['workflowState'] === "PUBLISHED_WITH_MOD") {
            $workflowStateExtra = $this->workflowStateExtraRepository->findOneBy([
                'code' => $filteredParams['workflowStateExtra'],
            ]);

            if ($workflowStateExtra) {
                $idea->setWorkflowStateExtra($workflowStateExtra);
            } else {
                $idea->setWorkflowStateExtra(null);
            }
        }

        $idea->setUpdatedAt($date);

        $this->em->flush();
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

    public function sendIdeaConfirmationEmail(UserInterface $user, IdeaInterface $idea): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Sikeres ötlet beküldés');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'idea'             => [
                    'title'       => $idea->getTitle(),
                    'solution'    => $idea->getSolution(),
                    'description' => $idea->getDescription(),
                ],
            ];

            $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create('idea-confirmation', $tplData)
            );

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Idea confirmation notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    public function sendIdeaWorkflowPublished(IdeaInterface $idea): void
    {
        $this->mailAdapter->clear();

        $user = $idea->getSubmitter();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Ötletedet közzétettük az otlet.budapest.hu-n');

            $tplData = [
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'ideaId'           => $idea->getId(),
                'ideaTitle'        => $idea->getTitle(),
                'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
            ];

            $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create('workflow-idea-published', $tplData)
            );

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Idea published notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    public function sendIdeaWorkflowPublishedWithMod(IdeaInterface $idea): void
    {
        $this->mailAdapter->clear();

        $user = $idea->getSubmitter();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Ötletedet közzétettük az otlet.budapest.hu-n');

            $extra = $idea->getWorkflowStateExtra() ? $idea->getWorkflowStateExtra()->getEmailText() : '';

            $tplData = [
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'ideaId'           => $idea->getId(),
                'ideaTitle'        => $idea->getTitle(),
                'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
                'ideaModText'      => $extra,
                'ideaModFullText'  => wordwrap($extra, 78, "\n"),
            ];

            $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create('workflow-idea-published-mod', $tplData)
            );

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Idea published notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    public function sendIdeaWorkflowTrashed(IdeaInterface $idea): void
    {
        $this->mailAdapter->clear();

        $user = $idea->getSubmitter();

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject('Ötletedet nem tudtuk közzétenni az otlet.budapest.hu-n');

            $tplData = [
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'ideaTitle'        => $idea->getTitle(),
            ];

            $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create('workflow-idea-rejected', $tplData)
            );

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Idea rejected notification no added to MailQueueService', [
                'extra' => $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }
}
