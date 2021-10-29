<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\Project;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project->setCampaignTheme(
            $this->getReference('campaign-location-active-1')
        );
        $project->addTag(
            $this->getReference('tag-active-1')
        );
        $project->setTitle('Bokrok telepítése fű helyett a zöldsávokba');
        $project->setDescription('Fű helyett bokrokat telepítsenek a járdaszigetekre, vagy a járda és az úttest között lévő zöldsávokba külső kerületekben, ahol ezek a felületek a legsérülékenyebbek.');
        $project->setCost(50000000);
        $project->setWorkflowState(
            $this->getReference('workflow-state-1')
        );
        $project->setLocation('Nem köthető konkrét helyszínhez');
        $project->setSolution('Esztétikusabb és a tűrőképessége is magasabb ezeknek a bokroknak a porral és az időjárással szemben, nem tapossák le a járókelők, nem parkolják le az autók, oxigént termel, port köt meg.');
        $project->setActive(true);
        $project->setCreatedAt(new DateTime());
        $project->setUpdatedAt(new DateTime());

        $manager->persist($project);
        $manager->flush();

        $this->addReference('project-active-1', $project);
    }
}
