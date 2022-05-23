<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\Campaign;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampaignDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $campaign = new Campaign();
        $campaign->setTitle('Budapest Részvételi költségvetés');
        $campaign->setShortTitle('2020/2021');
        $campaign->setDescription('Budapest Részvételi költségvetés 2020');
        $campaign->setActive(true);
        $campaign->setCreatedAt(new DateTime());
        $campaign->setUpdatedAt(new DateTime());

        $manager->persist($campaign);
        $manager->flush();

        $this->addReference('campaign-active-1', $campaign);
    }
}
