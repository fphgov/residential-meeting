<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\IllegalArgumentException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Laminas\InputFilter\InputFilterInterface;

final class PasswordStoreService implements PasswordStoreServiceInterface
{
    /** @var InputFilterInterface */
    private $inputFilter;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    protected $userRepository;

    public function __construct(
        InputFilterInterface $inputFilter,
        EntityManagerInterface $em
    ) {
        $this->inputFilter    = $inputFilter;
        $this->em             = $em;
        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function dummy(): bool
    {
        return true;
    }
}
