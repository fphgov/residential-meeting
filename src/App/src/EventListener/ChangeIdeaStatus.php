<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Idea;
use App\Entity\WorkflowStateInterface;
use App\Service\IdeaServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ChangeIdeaStatus implements EventSubscriber
{
    public function __construct() {

    }

    public function getSubscribedEvents()
    {
        return [
            Events::postUpdate,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args, $ideaService = null)
    {
        $em   = $args->getObjectManager();

        $uow  = $em->getUnitOfWork();
        $idea = $args->getObject();

        $changedValues = $uow->getEntityChangeSet($idea);

        if ($idea instanceof Idea && isset($changedValues['workflowState'])) {
            if ($changedValues['workflowState'][0]->getId() !== $changedValues['workflowState'][1]->getId()) {
                $workflowState = $idea->getWorkflowState();

                if ($workflowState->getId() === WorkflowStateInterface::STATUS_PUBLISHED) {
                    $ideaService->sendIdeaWorkflowPublished($idea);
                }

                if ($workflowState->getId() === WorkflowStateInterface::STATUS_PUBLISHED_WITH_MOD) {
                    $ideaService->sendIdeaWorkflowPublishedWithMod($idea);
                }

                if ($workflowState->getId() === WorkflowStateInterface::STATUS_TRASH) {
                    $ideaService->sendIdeaWorkflowTrashed($idea);
                }
            }
        }
    }
}
