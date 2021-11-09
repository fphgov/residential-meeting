<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Idea;
use App\Service\IdeaServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Container\ContainerInterface;

class ChangeIdeaStatusDelegator extends ChangeIdeaStatus
{
    protected ContainerInterface $container;

    protected EventSubscriber $subscriber;

    public function __construct(ContainerInterface $container, EventSubscriber $subscriber)
    {
        $this->container = $container;
        $this->subscriber = $subscriber;

        parent::__construct();
    }

    public function index(LifecycleEventArgs $args, $ideaService = null)
    {
        $ideaService = $this->container->get(IdeaServiceInterface::class);

        return $this->subscriber->index($args, $ideaService);
    }
}
