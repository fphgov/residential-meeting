<?php

declare(strict_types=1);

namespace AppTest\Service;

use App\Service\PasswordStoreServiceInterface;
use App\Repository\UserRepository;
use App\Exception\IllegalArgumentException;
use AppTest\AbstractServiceTest;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineFixture\FixtureManager;
use PHPUnit\Framework\TestCase;

final class PasswordStoreServiceTest extends AbstractServiceTest
{
    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(
            FixtureManager::getEntityManager(),
            new ClassMetadata(User::class)
        );

        $this->fixtureExecutor = FixtureManager::getFixtureExecutor();
    }

    public function testReturnTrue()
    {
        $passwordStoreService = self::$container->get(PasswordStoreServiceInterface::class);

        $this->assertTrue($passwordStoreService->dummy());
    }
}
