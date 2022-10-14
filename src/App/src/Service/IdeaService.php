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
use App\Exception\IdeaNotFoundException;
use App\Exception\NoHasPhaseCategoryException;
use App\Exception\NotPossibleSubmitIdeaWithAdminAccountException;
use App\Exception\WorkflowStateExtraNotFoundException;
use App\Exception\WorkflowStateNotFoundException;
use App\Model\IdeaEmailImportModel;
use App\Model\IdeaEmailModel;
use App\Model\IdeaEmailModelInterface;
use App\Service\MailServiceInterface;
use App\Service\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

use function basename;
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

    /** @var MailServiceInterface */
    private $mailService;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        PhaseServiceInterface $phaseService,
        MailServiceInterface $mailService
    ) {
        $this->config                       = $config;
        $this->em                           = $em;
        $this->phaseService                 = $phaseService;
        $this->mailService                  = $mailService;
        $this->ideaRepository               = $this->em->getRepository(Idea::class);
        $this->campaignThemeRepository      = $this->em->getRepository(CampaignTheme::class);
        $this->campaignLocationRepository   = $this->em->getRepository(CampaignLocation::class);
        $this->workflowStateRepository      = $this->em->getRepository(WorkflowState::class);
        $this->workflowStateExtraRepository = $this->em->getRepository(WorkflowStateExtra::class);
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

        if (isset($filteredParams['medias']) && is_array($filteredParams['medias'])) {
            $this->addAttachments($idea, $filteredParams['medias'], $date);
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

        if (isset($filteredParams['medias']) && is_array($filteredParams['medias'])) {
            $this->addAttachments($idea, $filteredParams['medias'], $date);
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

    public function importIdeaEmails(StreamInterface $stream)
    {
        $ideaEmailImportModel = new IdeaEmailImportModel();
        $ideaEmailImportModel->import($stream);

        $ideaEmails = $ideaEmailImportModel->getData();

        if (isset($ideaEmails[1])) {
            unset($ideaEmails[1]);
        }

        foreach ($ideaEmails as $ideaEmail) {
            $ideaEmailModel = new IdeaEmailModel($ideaEmail);

            $this->modificationIdea($ideaEmailModel);
        }

        $this->em->flush();
    }

    private function modificationIdea(IdeaEmailModelInterface $ideaEmailModel)
    {
        $idea = $this->ideaRepository->find($ideaEmailModel->getId());

        if (! $idea instanceof Idea) {
            throw new IdeaNotFoundException(
                'Idea not found | (Idea: ' . $ideaEmailModel->getId() . ')'
            );
        }

        $workflowState = $this->workflowStateRepository->find(
            $ideaEmailModel->getWorkflowStateId()
        );

        if (! $workflowState instanceof WorkflowState) {
            throw new WorkflowStateNotFoundException(
                'WorkflowState not found | (Idea: ' . $ideaEmailModel->getId() . ') ' . $ideaEmailModel->getWorkflowStateId()
            );
        }

        $idea->setWorkflowState($workflowState);

        if ($ideaEmailModel->getWorkflowStateExtraId() !== null) {
            $workflowStateExtra = $this->workflowStateExtraRepository->find(
                $ideaEmailModel->getWorkflowStateExtraId()
            );

            if (! $workflowStateExtra instanceof WorkflowStateExtra) {
                throw new WorkflowStateExtraNotFoundException(
                    'WorkflowStateExtra not found | (Idea: ' . $ideaEmailModel->getId() . ') ' . $ideaEmailModel->getWorkflowStateExtraId()
                );
            }

            $idea->setWorkflowStateExtra($workflowStateExtra);
        }

        $this->sendIdeaEmail($ideaEmailModel, $idea);
    }

    private function sendIdeaEmail(IdeaEmailModelInterface $ideaEmailModel, IdeaInterface $idea)
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'ideaTitle'        => $idea->getTitle(),
            'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
        ];

        if ($idea->getProject()) {
            $tplData['projectTitle'] = $idea->getProject()->getTitle();
            $tplData['projectLink']  = $this->config['app']['url'] . '/projektek/' . $idea->getProject()->getId();
        }

        $this->mailService->sendRaw($ideaEmailModel->getEmailContent(), $tplData, $idea->getSubmitter());
    }

    public function sendIdeaConfirmationEmail(UserInterface $user, IdeaInterface $idea): void
    {
        $tplData = [
            'firstname'        => $user->getFirstname(),
            'lastname'         => $user->getLastname(),
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'idea'             => [
                'title'       => $idea->getTitle(),
                'solution'    => $idea->getSolution(),
                'description' => $idea->getDescription(),
            ],
        ];

        $this->mailService->send('idea-confirmation', $tplData, $user);
    }

    public function sendIdeaWorkflowPublished(IdeaInterface $idea): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'ideaId'           => $idea->getId(),
            'ideaTitle'        => $idea->getTitle(),
            'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
        ];

        $this->mailService->send('workflow-idea-published', $tplData, $idea->getSubmitter());
    }

    public function sendIdeaWorkflowPublishedWithMod(IdeaInterface $idea): void
    {
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

        $this->mailService->send('workflow-idea-published-mod', $tplData, $idea->getSubmitter());
    }

    public function sendIdeaWorkflowTrashed(IdeaInterface $idea): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'ideaTitle'        => $idea->getTitle(),
        ];

        $this->mailService->send('workflow-idea-rejected', $tplData, $idea->getSubmitter());
    }

    public function sendIdeaWorkflowProfessionalTrashed(IdeaInterface $idea): void
    {
        $tplData = [
            'infoMunicipality' => $this->config['app']['municipality'],
            'infoEmail'        => $this->config['app']['email'],
            'ideaTitle'        => $idea->getTitle(),
            'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
        ];

        $this->mailService->send('workflow-idea-professional-rejected', $tplData, $idea->getSubmitter());
    }

    public function sendIdeaWorkflowProjectRejected(IdeaInterface $idea): void
    {
        $project = $idea->getProject();

        if ($project !== null) {
            $tplData = [
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'ideaTitle'        => $idea->getTitle(),
                'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
                'projectTitle'     => $project->getTitle(),
                'projectLink'      => $this->config['app']['url'] . '/projektek/' . $project->getId(),
            ];

            $this->mailService->send('workflow-project-idea-rejected', $tplData, $idea->getSubmitter());
        }
    }

    public function sendIdeaWorkflowVotingListed(IdeaInterface $idea): void
    {
        $project = $idea->getProject();

        if ($project !== null) {
            $tplData = [
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'ideaTitle'        => $idea->getTitle(),
                'ideaLink'         => $this->config['app']['url'] . '/otletek/' . $idea->getId(),
                'projectTitle'     => $project->getTitle(),
                'projectLink'      => $this->config['app']['url'] . '/projektek/' . $project->getId(),
            ];

            $this->mailService->send('workflow-project-idea-accepted', $tplData, $idea->getSubmitter());
        }
    }
}
