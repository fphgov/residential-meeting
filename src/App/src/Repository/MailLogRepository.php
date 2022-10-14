<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;

final class MailLogRepository extends EntityRepository
{
    public function getSendedAccountConfirmation(DateTime $date): array
    {
        $qb = $this->createQueryBuilder('ml');

        $qb
            ->where('ml.name = :name')
            ->andWhere('ml.createdAt >= :date')
            ->setParameters([
                'name' => 'account-confirmation',
                'date' => $date,
            ]);

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function isSendedReminder(User $user, DateTime $date): bool
    {
        $qb = $this->createQueryBuilder('ml');

        $qb
            ->where('ml.user = :user')
            ->andWhere('ml.name = :name')
            ->andWhere('ml.createdAt >= :date')
            ->setParameters([
                'user' => $user,
                'name' => 'account-confirmation-reminder',
                'date' => $date,
            ]);

        $result = $qb->getQuery()->getResult();

        return count($result) > 0;
    }
}
