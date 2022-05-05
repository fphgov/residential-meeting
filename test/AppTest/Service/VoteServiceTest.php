<?php

declare(strict_types=1);

namespace AppTest\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use AppTest\AbstractServiceTest;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineFixture\CampaignDataLoader;
use DoctrineFixture\CampaignLocationDataLoader;
use DoctrineFixture\CampaignThemeDataLoader;
use DoctrineFixture\FixtureManager;
use DoctrineFixture\IdeaDataLoader;
use DoctrineFixture\TagDataLoader;
use DoctrineFixture\ProjectDataLoader;
use DoctrineFixture\UserDataLoader;
use DoctrineFixture\WorkflowStateDataLoader;

final class VoteServiceTest extends AbstractServiceTest
{
    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(
            FixtureManager::getEntityManager(),
            new ClassMetadata(User::class)
        );

        $this->fixtureExecutor = FixtureManager::getFixtureExecutor();

        $this->fixtureExecutor->execute([
            new WorkflowStateDataLoader(),
            new TagDataLoader(),
            new CampaignDataLoader(),
            new CampaignLocationDataLoader(),
            new CampaignThemeDataLoader(),
            new UserDataLoader(),
            new IdeaDataLoader(),
            new ProjectDataLoader(),
        ]);
    }

    public function testVote()
    {

    }
}
