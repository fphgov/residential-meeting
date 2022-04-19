<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Idea;
use App\Entity\IdeaInterface;
use App\Entity\WorkflowState;
use App\Entity\WorkflowStateExtra;
use App\Model\IdeaAnswerImportModel;
use App\Model\IdeaAnswerModel;
use App\Exception\IdeaNotFoundException;
use App\Exception\WorkflowStateNotFoundException;
use App\Exception\WorkflowStateExtraNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\StreamInterface;

final class IdeaAnswerService implements IdeaAnswerServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    private $ideaRepository;

    public function __construct(
        EntityManagerInterface $em
    ){
        $this->em                           = $em;
        $this->ideaRepository               = $this->em->getRepository(Idea::class);
        $this->workflowStateRepository      = $this->em->getRepository(WorkflowState::class);
        $this->workflowStateExtraRepository = $this->em->getRepository(WorkflowStateExtra::class);
    }

    public function importIdeaAnswers(StreamInterface $stream)
    {
        $ideaAnswerImportModel = new IdeaAnswerImportModel();
        $ideaAnswerImportModel->import($stream);

        $answers = $ideaAnswerImportModel->getData();

        if (isset($answers[1])) {
            unset($answers[1]);
        }

        foreach ($answers as $answer) {
            $ideaAnswerModel = new IdeaAnswerModel($answer);

            $this->modificationIdea($ideaAnswerModel);
        }

        $this->em->flush();
    }

    private function modificationIdea(IdeaAnswerModel $ideaAnswerModel)
    {
        $idea = $this->ideaRepository->find($ideaAnswerModel->getId());

        if (! $idea instanceof Idea) {
            throw new IdeaNotFoundException(
                'Idea not found | (Idea: ' . $ideaAnswerModel->getId() . ')'
            );
        }

        $workflowState = $this->workflowStateRepository->find(
            $ideaAnswerModel->getWorkflowStateId()
        );

        if (! $workflowState instanceof WorkflowState) {
            throw new WorkflowStateNotFoundException(
                'WorkflowState not found | (Idea: ' . $ideaAnswerModel->getId() . ') ' . $ideaAnswerModel->getWorkflowStateId()
            );
        }

        $idea->setAnswer($ideaAnswerModel->getAnswer());
        $idea->setWorkflowState($workflowState);

        if ($ideaAnswerModel->getWorkflowStateExtraId() !== null) {
            $workflowStateExtra = $this->workflowStateExtraRepository->find(
                $ideaAnswerModel->getWorkflowStateExtraId()
            );

            if (! $workflowStateExtra instanceof WorkflowStateExtra) {
                throw new WorkflowStateExtraNotFoundException(
                    'WorkflowStateExtra not found | (Idea: ' . $ideaAnswerModel->getId() . ') ' . $ideaAnswerModel->getWorkflowStateExtraId()
                );
            }

            $idea->setWorkflowStateExtra($workflowStateExtra);
        }
    }
}
