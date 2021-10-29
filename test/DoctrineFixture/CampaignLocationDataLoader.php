<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\CampaignLocation;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampaignLocationDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $campaignLocation = new CampaignLocation();
        $campaignLocation->setCampaign(
            $this->getReference('campaign-active-1')
        );
        $campaignLocation->setCode('AREA0');
        $campaignLocation->setName('Egész Budapest');
        $campaignLocation->setDescription('Budapest teljes területe');
        $campaignLocation->setActive(true);
        $campaignLocation->setCreatedAt(new DateTime());
        $campaignLocation->setUpdatedAt(new DateTime());

        $manager->persist($campaignLocation);
        $manager->flush();

        $this->addReference('campaign-location-active-1', $campaignLocation);
    }
}
