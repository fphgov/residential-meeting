<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\Mail;
use App\Helper\MailContentHelper;
use App\Helper\MailContentRawHelper;
use App\Repository\MailRepository;
use App\Service\MailQueueServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;
use Mail\MailAdapterInterface;
use Mail\Model\EmailContentModelInterface;
use Throwable;

use function basename;
use function error_log;
use function file_get_contents;
use function getenv;

class MailService implements MailServiceInterface
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

    /** @var MailContentRawHelper */
    private $mailContentRawHelper;

    /** @var MailQueueServiceInterface */
    private $mailQueueService;

    public function __construct(
        EntityManagerInterface $em,
        Logger $audit,
        MailAdapterInterface $mailAdapter,
        MailContentHelper $mailContentHelper,
        MailContentRawHelper $mailContentRawHelper,
        MailQueueServiceInterface $mailQueueService
    ) {
        $this->em                   = $em;
        $this->audit                = $audit;
        $this->mailAdapter          = $mailAdapter;
        $this->mailContentHelper    = $mailContentHelper;
        $this->mailContentRawHelper = $mailContentRawHelper;
        $this->mailQueueService     = $mailQueueService;
        $this->mailRepository       = $this->em->getRepository(Mail::class);
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

    public function send(string $mailCode, array $tplData, Account $account): void
    {
        $this->mailAdapter->clear();

        $mail = $this->mailRepository->findOneBy([
            'code' => $mailCode,
        ]);

        try {
            $this->mailAdapter->getMessage()->addTo($account->getEmail());
            $this->mailAdapter->getMessage()->setSubject($mail->getSubject());

            $layout = $this->getLayout();

            if ($layout) {
                $this->mailAdapter->setLayout($layout);
                $this->mailAdapter->setCss($this->getCss());
            }

            $template = $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create($mailCode, $tplData)
            );

            if ($layout) {
                $template->addImage(basename($this->getHeaderImagePath()), $this->getHeaderImagePath());
            }

            $this->mailQueueService->add($account, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Notification no added to MailQueueService', [
                'extra' => $mailCode . " | " . $account->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    public function sendRaw(EmailContentModelInterface $emailContentModel, array $tplData, Account $account): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($account->getEmail());
            $this->mailAdapter->getMessage()->setSubject($emailContentModel->getSubject());

            $layout = $this->getLayout();

            if ($layout) {
                $this->mailAdapter->setLayout($layout);
                $this->mailAdapter->setCss($this->getCss());
            }

            $template = $this->mailAdapter->setTemplate(
                $this->mailContentRawHelper->create($emailContentModel, $tplData)
            );

            if ($layout) {
                $template->addImage(basename($this->getHeaderImagePath()), $this->getHeaderImagePath());
            }

            $this->mailQueueService->add($account, $this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            $this->audit->err('Notification raw no added to MailQueueService', [
                'extra' => $emailContentModel->getSubject() . " | " . $account->getId() . " | " . $e->getMessage(),
            ]);
        }
    }

    private function getCss(): string
    {
        return file_get_contents(getenv('APP_EMAIL_TEMPLATE') . '/style.css');
    }

    private function getHeaderImagePath(): string
    {
        return getenv('APP_EMAIL_TEMPLATE') . '/logo.png';
    }

    private function getLayout(): string|bool
    {
        return file_get_contents(getenv('APP_EMAIL_TEMPLATE') . '/layout.html');
    }
}
