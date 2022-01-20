<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use App\Repository\MailRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

final class MailService implements MailServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var MailRepository */
    private $mailRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em             = $em;
        $this->mailRepository = $this->em->getRepository(Mail::class);
    }

    public function getRepository(): MailRepository
    {
        return $this->mailRepository;
    }

    public function modifyMail(Mail $mail, array $filteredParams): void
    {
        $date = new DateTime();

        if (isset($filteredParams['html'])) {
            $mail->setHtml($filteredParams['html']);
        }

        if (isset($filteredParams['plainText'])) {
            $mail->setPlainText($filteredParams['plainText']);
        }

        $mail->setUpdatedAt($date);

        $this->em->flush();
    }
}
