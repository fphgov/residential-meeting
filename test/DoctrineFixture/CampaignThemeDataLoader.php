<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\CampaignTheme;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampaignThemeDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $campaignTheme = new CampaignTheme();
        $campaignTheme->setCampaign(
            $this->getReference('campaign-active-1')
        );
        $campaignTheme->setCode('GREEN');
        $campaignTheme->setName('Zöld Budapest');
        $campaignTheme->setDescription('Zöld kérdések');
        $campaignTheme->setRgb('#72be97');
        $campaignTheme->setActive(true);
        $campaignTheme->setCreatedAt(new DateTime());
        $campaignTheme->setUpdatedAt(new DateTime());

        $manager->persist($campaignTheme);
        $manager->flush();

        $this->addReference('campaign-theme-active-1', $campaignTheme);
    }
}
