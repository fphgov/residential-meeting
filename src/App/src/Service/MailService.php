<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mail;
use App\Helper\MailContentHelper;
use App\Helper\MailContentRawHelper;
use App\Repository\MailRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Log\Logger;
use Mail\MailAdapterInterface;
use Mail\Entity\EmailNotificationInterface;
use Mail\Model\EmailContentModelInterface;
use Throwable;

use function basename;
use function file_get_contents;
use function getenv;

class MailService implements MailServiceInterface
{
    /** @var MailRepository */
    private $mailRepository;

    public function __construct(
        private EntityManagerInterface $em,
        private Logger $audit,
        private MailAdapterInterface $mailAdapter,
        private MailContentHelper $mailContentHelper,
        private MailContentRawHelper $mailContentRawHelper
    ) {
        $this->em                   = $em;
        $this->audit                = $audit;
        $this->mailAdapter          = $mailAdapter;
        $this->mailContentHelper    = $mailContentHelper;
        $this->mailContentRawHelper = $mailContentRawHelper;
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

    public function send(
        array $tplData,
        EmailNotificationInterface $notification,
        bool $useException = false
    ): void
    {
        $this->mailAdapter->clear();

        $mail = $this->mailRepository->findOneBy([
            'code' => $notification->getEmailCode(),
        ]);

        try {
            $this->mailAdapter->getMessage()->addTo($notification->getEmail());
            $this->mailAdapter->getMessage()->setSubject($mail->getSubject());

            $layout = $this->getLayout();

            if ($layout) {
                $this->mailAdapter->setLayout($layout);
                $this->mailAdapter->setCss($this->getCss());
            }

            $template = $this->mailAdapter->setTemplate(
                $this->mailContentHelper->create($notification->getEmailCode(), $tplData)
            );

            if ($layout) {
                $template->addImage(basename($this->getHeaderImagePath()), $this->getHeaderImagePath());
            }

            $this->mailAdapter->send();
        } catch (Throwable $e) {
            $this->audit->err('Notification no send', [
                'extra' => $notification->getEmailCode() . " | " . $notification->getId() . " | " . $e->getMessage(),
            ]);

            if ($useException) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
        }
    }

    public function sendRaw(
        EmailContentModelInterface $emailContentModel,
        array $tplData,
        EmailNotificationInterface $notification,
        bool $useException = false
    ): void
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->getMessage()->addTo($notification->getEmail());
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

            $this->mailAdapter->send();
        } catch (Throwable $e) {
            $this->audit->err('Notification raw no send', [
                'extra' => $emailContentModel->getSubject() . " | " . $notification->getId() . " | " . $e->getMessage(),
            ]);

            if ($useException) {
                throw new Exception($e->getMessage(), $e->getCode());
            }
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
