<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use App\Entity\User;
use App\Helper\MailContentHelper;
use App\Repository\MailRepository;
use App\Service\MailQueueServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;
use Mail\MailAdapterInterface;
use Throwable;

use function error_log;

final class MailService implements MailServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var MailRepository */
    private $mailRepository;

    /** @var Logger */
    private $audit;

    /** @var MailAdapterInterface */
    private $mailAdapter;

    /** @var MailContentHelper */
    private $mailContentHelper;

    /** @var MailQueueServiceInterface */
    private $mailQueueService;

    public function __construct(
        EntityManagerInterface $em,
        Logger $audit,
        MailAdapterInterface $mailAdapter,
        MailContentHelper $mailContentHelper,
        MailQueueServiceInterface $mailQueueService
    ) {
        $this->em                = $em;
        $this->audit             = $audit;
        $this->mailAdapter       = $mailAdapter;
        $this->mailContentHelper = $mailContentHelper;
        $this->mailQueueService  = $mailQueueService;
        $this->mailRepository    = $this->em->getRepository(Mail::class);
    }

    public function getRepository(): MailRepository
    {
        return $this->mailRepository;
    }

    public function modifyMail(Mail $mail, array $filteredParams): void
    {
        $date = new DateTime();

        if (isset($filteredParams['subject'])) {
            $mail->setSubject($filteredParams['subject']);
        }

        if (isset($filteredParams['html'])) {
            $mail->setHtml($filteredParams['html']);
        }

        if (isset($filteredParams['plainText'])) {
            $mail->setPlainText($filteredParams['plainText']);
        }

        $mail->setUpdatedAt($date);

        $this->em->flush();
    }

    public function send(string $mailCode, array $tplData, User $user): void
    {
        $this->mailAdapter->clear();

        $mail = $this->mailRepository->findOneBy([
            'code' => $mailCode,
        ]);

        try {
            $this->mailAdapter->getMessage()->addTo($user->getEmail());
            $this->mailAdapter->getMessage()->setSubject($mail->getSubject());

            $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create($mailCode, $tplData)
            );

            $this->mailQueueService->add($user, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Notification no added to MailQueueService', [
                'extra' => $mailCode . " | " . $user->getId() . " | " . $e->getMessage(),
            ]);
        }
    }
}
