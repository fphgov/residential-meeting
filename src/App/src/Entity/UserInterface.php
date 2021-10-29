<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface UserInterface extends EntityInterface, EntityActiveInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'userPreference',
        'vote',
        'password',
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setUserPreference(?UserPreference $userPreference = null): void;

    public function getUserPreference(): ?UserPreference;

    public function getVoteCollection(): Collection;

    public function getVotes(): array;

    public function addVote(VoteInterface $vote): self;

    public function getIdeaCollection(): Collection;

    public function getIdeas(): array;

    public function addIdea(IdeaInterface $idea): self;

    public function setUsername(string $username): void;

    public function getUsername(): string;

    public function setFirstname(string $firstname): void;

    public function getFirstname(): string;

    public function setLastname(string $lastname): void;

    public function getLastname(): string;

    public function setEmail(string $email): void;

    public function getEmail(): string;

    public function setPassword(string $password): void;

    public function getPassword(): string;

    public function setRole(string $role): void;

    public function getRole(): ?string;

    public function setHash(?string $hash = null): void;

    public function getHash(): ?string;

    public function generateToken(): string;
}
