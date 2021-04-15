<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;
use Mail\MailAdapter;
use Mezzio\Template\TemplateRendererInterface;
use Throwable;

use function preg_match;
use function strip_tags;

final class NoticeService extends AbstractNoticeService implements NoticeServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var MailAdapter */
    private $mailAdapter;

    /** @var TemplateRendererInterface */
    private $template;

    /** @var Logger */
    private $audit;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        MailAdapter $mailAdapter,
        Logger $audit,
        ?TemplateRendererInterface $template = null
    ) {
        $this->config      = $config;
        $this->em          = $em;
        $this->mailAdapter = $mailAdapter;
        $this->audit       = $audit;
        $this->template    = $template;
    }

    public function sendEmail(): void
    {
        $this->mailAdapter->clear();

        // $appointment = $applicant->getAppointment();

        // try {
        //     $this->mailAdapter->message->addTo($applicant->getEmail());
        //     $this->mailAdapter->message->setSubject($this->config['app']['notification']['mail']['subject']);
        //     $this->mailAdapter->message->addReplyTo($this->config['app']['notification']['mail']['replayTo']);

        //     $tplData = [
        //         'infoMunicipality'     => $this->config['app']['municipality'],
        //         'infoPhone'            => $this->config['app']['phone'],
        //         'infoEmail'            => $this->config['app']['email'],
        //         'infoUrl'              => $this->config['app']['url'],
        //     ];

        //     $description = $this->config['app']['ics']['description'];

        //     if ($this->template !== null) {
        //         $description = strip_tags($this->template->render('email/created', $tplData));
        //     }

        //     $applicant->setNotified(true);

        //     $this->em->flush();
        // } catch (Throwable $e) {
        //     $this->audit->err('Mail no sended new applicant', [
        //         'extra' => $applicant->getHumanId(),
        //     ]);
        // }
    }
}
