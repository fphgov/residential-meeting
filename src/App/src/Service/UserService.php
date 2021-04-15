<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserService implements UserServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em             = $em;
        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function getRepository(): EntityRepository
    {
        return $this->userRepository;
    }
}
