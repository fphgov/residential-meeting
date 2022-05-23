<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\User;
use App\Entity\UserPreference;
use App\Model\PBKDF2Password;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $passwordModel    = new PBKDF2Password('password', PBKDF2Password::PW_REPRESENTATION_CLEARTEXT);
        $storablePassword = $passwordModel->getStorableRepresentation();

        $user           = new User();
        $userPreference = new UserPreference();

        $user->setUserPreference($userPreference);
        $user->setUsername('random-123');
        $user->setFirstname('John');
        $user->setLastname('Smith');
        $user->setEmail('hello@example.com');
        $user->setPassword($storablePassword);
        $user->setActive(true);
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());

        $userPreference->setBirthyear(1990);
        $userPreference->setLiveInCity(true);
        $userPreference->setPostalCode("1052");
        $userPreference->setHearAbout('facebook');
        $userPreference->setPrivacy(true);
        $userPreference->setPrize(false);
        $userPreference->setPrizeHash(null);
        $userPreference->setCampaignEmail(false);
        $userPreference->setUser($user);
        $userPreference->setCreatedAt(new DateTime());
        $userPreference->setUpdatedAt(new DateTime());

        $manager->persist($userPreference);
        $manager->persist($user);
        $manager->flush();

        $this->addReference('user-preference-active-1', $userPreference);
        $this->addReference('user-active-1', $user);
    }
}
