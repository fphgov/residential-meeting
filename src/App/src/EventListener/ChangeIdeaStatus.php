<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Idea;
use App\Service\IdeaServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
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
        $idea = $args->getObject();

        if ($idea instanceof Idea) {
            // $entityManager = $args->getObjectManager();

            $ideaService->sendIdeaWorkflowPublished($idea);
        }
    }
}
