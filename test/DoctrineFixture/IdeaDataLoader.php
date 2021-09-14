<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\Idea;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class IdeaDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $idea = new Idea();
        $idea->setCampaignTheme(
            $this->getReference('campaign-theme-active-1')
        );
        $idea->setCampaignLocation(
            $this->getReference('campaign-location-active-1')
        );
        $idea->setSubmitter(
            $this->getReference('user-active-1')
        );
        $idea->setTitle('Bokrok telepítése fű helyett a zöldsávokba');
        $idea->setDescription('Fű helyett bokrokat telepítsenek a járdaszigetekre, vagy a járda és az úttest között lévő zöldsávokba külső kerületekben, ahol ezek a felületek a legsérülékenyebbek.');
        $idea->setParticipate(false);
        $idea->setCost(50000000);
        $idea->setWorkflowState(
            $this->getReference('workflow-state-1')
        );
        $idea->setActive(true);
        $idea->setCreatedAt(new DateTime());
        $idea->setUpdatedAt(new DateTime());

        $manager->persist($idea);
        $manager->flush();

        $this->addReference('idea-active-1', $idea);
    }
}
