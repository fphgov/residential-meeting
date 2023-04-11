<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountInterface;

interface VoteServiceInterface
{
    public function voting(
        AccountInterface $user,
        array $filteredData
    ): void;

    public function checkVoteable(AccountInterface $account): void;
}
