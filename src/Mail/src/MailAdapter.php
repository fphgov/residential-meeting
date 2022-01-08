<?php

declare(strict_types=1);

namespace Mail;

use Laminas\Mail\Header;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;
use Mail\Entity\MailInterface;
use Mail\Helper\MailContentHelperInterface;
use Throwable;

use function error_log;
use function is_array;
use function uniqid;

class MailAdapter implements MailAdapterInterface
{
    /** @var array */
    private $config;

    /** @var Smtp */
    private $transport;

    /** @var Message */
    private $message;

    /** @var MimeMessage */
    private $content;

    /** @var string */
    private $messageId = '';

    /** @var string */
    private $name = '';

    public function __construct(
        Smtp $transport,
        array $config
    ) {
        $this->transport = $transport;
        $this->config    = $config;

        $this->clear();
    }

    public function setTemplate(MailContentHelperInterface $mailContent): self
    {
        $this->name = $mailContent->getName();

        $this->content = new MimeMessage();

        $text = $mailContent->render(MailInterface::FORMAT_TEXT);

        $bodyText           = new MimePart($text);
        $bodyText->type     = Mime::TYPE_TEXT;
        $bodyText->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $bodyText->charset  = 'utf-8';

        $this->content->addPart($bodyText);

        $html = $mailContent->render(MailInterface::FORMAT_HTML);

        $bodyHtml           = new MimePart($html);
        $bodyHtml->type     = Mime::TYPE_HTML;
        $bodyHtml->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $bodyHtml->charset  = 'utf-8';

        $this->content->addPart($bodyHtml);

        return $this;
    }

    public function addPdfAttachment(string $filename, string $stream): self
    {
        $pdf              = new MimePart($stream);
        $pdf->type        = 'application/pdf';
        $pdf->filename    = $filename;
        $pdf->disposition = Mime::DISPOSITION_ATTACHMENT;
        $pdf->encoding    = Mime::ENCODING_BASE64;

        if ($this->content instanceof MimeMessage) {
            $this->content->addPart($pdf);
        }

        return $this;
    }

    public function addIcsAttachment(string $filename, string $stream): self
    {
        $ics              = new MimePart($stream);
        $ics->type        = 'text/calendar';
        $ics->filename    = $filename;
        $ics->disposition = Mime::DISPOSITION_ATTACHMENT;
        $ics->encoding    = Mime::ENCODING_BASE64;

        if ($this->content instanceof MimeMessage) {
            $this->content->addPart($ics);
        }

        return $this;
    }

    public function send(): void
    {
        $this->message->setBody($this->content);
        $this->message->setEncoding('UTF-8');

        $contentTypeHeader = $this->message->getHeaders()->get('Content-Type');

        if ($contentTypeHeader instanceof Header\ContentType) {
            $contentTypeHeader->setType('multipart/alternative');
        }

        $this->transport->send($this->message);
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getContent(): MimeMessage
    {
        return $this->content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function clear(): void
    {
        $this->name      = '';
        $this->messageId = '';
        $this->message   = new Message();

        if (is_array($this->config['defaults'])) {
            foreach ($this->config['defaults'] as $ck => $cv) {
                try {
                    $this->message->{$ck}($cv);
                } catch (Throwable $e) {
                    error_log($e->getMessage());
                }
            }
        }

        $this->setMessageId();
    }

    private function setMessageId(): void
    {
        $key = uniqid() . '@' . $this->config['headers']['message_id_domain'];

        $messageId = Header\MessageId::fromString('message-id: ' . $key);

        $this->messageId = $messageId->getFieldValue();

        $this->message->getHeaders()->removeHeader($messageId);
        $this->message->getHeaders()->addHeader($messageId);
    }
}
