<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\WorkflowState;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkflowStateDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $workflowState = new WorkflowState();
        $workflowState->setId(100);
        $workflowState->setTitle('Beküldött');
        $workflowState->setPrivateTitle('Beküldött');
        $workflowState->setCode('RECEIVED');
        $workflowState->setDescription('Beküldött');

        $manager->persist($workflowState);
        $manager->flush();

        $this->addReference('workflow-state-1', $workflowState);
    }
}
