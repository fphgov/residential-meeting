<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountInterface;

interface AccountServiceInterface
{
    public function getAccount(string $authCode): ?AccountInterface;
}
