<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class MailRepository extends EntityRepository
{
    public function getAllMail(): array
    {
        $mails = $this->findAll();

        $normalizedMails = [];
        foreach ($mails as $mail) {
            $normalizedMails[] = $mail->normalizer(null, ['groups' => 'option']);
        }

        return $normalizedMails;
    }
}
