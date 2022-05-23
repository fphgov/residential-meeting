<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\Phase;
use DateTime;
use DateInterval;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhaseDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $phase = new Phase();
        $phase->setId(6);
        $phase->setCampaign(
            $this->getReference('campaign-active-1')
        );
        $phase->setCode('VOTE');
        $phase->setTitle('Szavazás');
        $phase->setDescription('Szavazás');
        $phase->setStart(new DateTime());
        $phase->setEnd((new DateTime())->add(new DateInterval('P1M')));

        $manager->persist($phase);
        $manager->flush();

        $this->addReference('phase-active-1', $phase);
    }
}
