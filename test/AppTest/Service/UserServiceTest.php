<?php

declare(strict_types=1);

namespace AppTest\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use AppTest\AbstractServiceTest;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineFixture\CampaignDataLoader;
use DoctrineFixture\CampaignThemeDataLoader;
use DoctrineFixture\CampaignLocationDataLoader;
use DoctrineFixture\ProjectDataLoader;
use DoctrineFixture\IdeaDataLoader;
use DoctrineFixture\TagDataLoader;
use DoctrineFixture\UserDataLoader;
use DoctrineFixture\UserPreferenceDataLoader;
use DoctrineFixture\FixtureManager;

final class UserServiceTest extends AbstractServiceTest
{
    // protected function setUp(): void
    // {
    //     $this->userRepository = new UserRepository(
    //         FixtureManager::getEntityManager(),
    //         new ClassMetadata(User::class)
    //     );

    //     $this->fixtureExecutor = FixtureManager::getFixtureExecutor();
    // }

    // public function testReturnPlaceInstance()
    // {
    //     $this->fixtureExecutor->execute([
    //         new CampaignDataLoader(),
    //         new CampaignLocationDataLoader(),
    //         new CampaignThemeDataLoader(),
    //         new IdeaDataLoader(),
    //         new TagDataLoader(),
    //         new ProjectDataLoader(),
    //         new UserPreferenceDataLoader(),
    //         new UserDataLoader(),
    //     ]);

    //     $this->assertInstanceOf(User::class, $this->userRepository->find(1));
    // }

    public function testRemovable()
    {
        $this->assertInstanceOf(User::class, new User());
    }
}
