<?php

declare(strict_types=1);

namespace AppTest\Service;

use App\Entity\Phase;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\PhaseRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use App\Service\MailService;
use App\Service\PhaseService;
use App\Service\VoteService;
use App\Service\VoteServiceInterface;
use AppTest\AbstractServiceTest;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineFixture\CampaignDataLoader;
use DoctrineFixture\CampaignLocationDataLoader;
use DoctrineFixture\CampaignThemeDataLoader;
use DoctrineFixture\FixtureManager;
use DoctrineFixture\IdeaDataLoader;
use DoctrineFixture\PhaseDataLoader;
use DoctrineFixture\ProjectDataLoader;
use DoctrineFixture\TagDataLoader;
use DoctrineFixture\UserDataLoader;
use DoctrineFixture\VoteDataLoader;
use DoctrineFixture\VoteTypeDataLoader;
use DoctrineFixture\WorkflowStateDataLoader;

final class VoteServiceTest extends AbstractServiceTest
{
    protected function setUp(): void
    {
        $this->phaseRepository = new PhaseRepository(
            FixtureManager::getEntityManager(),
            new ClassMetadata(Phase::class)
        );

        $this->userRepository = new UserRepository(
            FixtureManager::getEntityManager(),
            new ClassMetadata(User::class)
        );

        $this->voteRepository = new VoteRepository(
            FixtureManager::getEntityManager(),
            new ClassMetadata(Vote::class)
        );

        $this->projectRepository = new ProjectRepository(
            FixtureManager::getEntityManager(),
            new ClassMetadata(Project::class)
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
            new PhaseDataLoader(),
            new VoteTypeDataLoader(),
            new VoteDataLoader(),
        ]);

        $this->createVoteService();
    }

    private function createVoteService(): void
    {
        $config = [
            'app' => [
                'municipality' => 'Municipality',
                'email'        => 'vote-service@localhost.com',
            ],
        ];

        $phaseService = $this->createMock(PhaseService::class);
        $phaseService
            ->expects($this->any())
            ->method('phaseCheck')
            ->willReturn($this->phaseRepository->findAll()[0]);

        $mailService = $this->createMock(MailService::class);
        $mailService
            ->expects($this->any())
            ->method('send');

        $this->voteService = new VoteService(
            $config,
            FixtureManager::getEntityManager(),
            $phaseService,
            $mailService
        );
    }

    public function testInitalized()
    {
        $this->assertInstanceOf(VoteServiceInterface::class, $this->voteService);
    }

    // public function testCreateVoteInDatabase()
    // {
    //     $em = FixtureManager::getEntityManager();

    //     $user     = $this->userRepository->findAll()[0];
    //     $votes    = $this->voteRepository->findAll();
    //     $projects = $this->projectRepository->findAll();

    //     $type = $em->getReference(VoteType::class, 2);

    //     $this->assertInstanceOf(UserInterface::class, $user);
    //     $this->assertInstanceOf(VoteTypeInterface::class, $type);

    //     $this->voteService->voting($user, $type, $projects);

    //     $this->assertEquals(count($votes) + 1, count($this->voteRepository->findAll()));
    // }
}
