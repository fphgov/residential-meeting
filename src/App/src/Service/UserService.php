<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

final class UserService implements UserServiceInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
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
