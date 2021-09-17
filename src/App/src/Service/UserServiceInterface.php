<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;

interface UserServiceInterface
{
    public function activate(string $hash): void;

    public function prizeActivate(string $prizeHash): void;

    public function resetPassword(string $hash, string $password): void;

    public function forgotPassword(string $email): void;

    public function registration(array $filteredParams): UserInterface;

    public function getRepository(): EntityRepository;
}
