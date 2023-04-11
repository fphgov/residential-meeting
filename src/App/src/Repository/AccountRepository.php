<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AccountInterface;
use App\Exception\AccountNotVotableException;
use Doctrine\ORM\EntityRepository;

final class AccountRepository extends EntityRepository
{
    public function getAccountByAuthCode(string $authCode): AccountInterface
    {
        $account = $this->findOneBy([
            'authCode' => $authCode,
        ]);

        if (! $account instanceof AccountInterface) {
            throw new AccountNotVotableException($authCode);
        }

        return $account;
    }
}
