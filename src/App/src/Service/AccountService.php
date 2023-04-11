<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\AccountInterface;
use App\Exception\AuthCodeNotFoundException;
use App\Repository\AccountRepository;
use App\Service\MailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;

final class AccountService implements AccountServiceInterface
{
    private AccountRepository $accountRepository;

    public function __construct(
        private array $config,
        private EntityManagerInterface $em,
        private Logger $audit,
        private MailServiceInterface $mailService
    ) {
        $this->config            = $config;
        $this->em                = $em;
        $this->audit             = $audit;
        $this->mailService       = $mailService;
        $this->accountRepository = $this->em->getRepository(Account::class);
    }

    public function getAccount(string $authCode): AccountInterface
    {
        $account = $this->accountRepository->findOneBy([
            'authCode' => $authCode,
        ]);

        if (! $account) {
            throw new AuthCodeNotFoundException('Unknow auth code ' . $authCode);
        }

        return $account;
    }
}
