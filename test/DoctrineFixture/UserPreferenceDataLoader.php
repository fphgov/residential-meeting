<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\UserPreference;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserPreferenceDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userPreference = new UserPreference();
        $userPreference->setBirthyear(1990);
        $userPreference->setLiveInCity(true);
        $userPreference->setPostalCode("1052");
        $userPreference->setHearAbout('facebook');
        $userPreference->setPrivacy(true);
        $userPreference->setPrize(false);
        $userPreference->setPrizeNotified(false);
        $userPreference->setPrizeNotifiedSec(false);
        $userPreference->setPrizeNotifiedThird(false);
        $userPreference->setPrizeHash(null);
        $userPreference->setCreated(null);
        $userPreference->setCampaignEmail(false);

        $manager->persist($userPreference);
        $manager->flush();

        $this->addReference('user-preference-active-1', $user);
    }
}
