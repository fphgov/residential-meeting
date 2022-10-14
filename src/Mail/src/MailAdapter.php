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
use Mail\Model\EmailTemplateRenderModelInterface;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Throwable;

use function error_log;
use function is_array;
use function uniqid;
use function str_replace;
use function file_get_contents;

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
    private $layout;

    /** @var string */
    private $css;

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

    public function setTemplate(EmailTemplateRenderModelInterface $mailContent): self
    {
        $this->name = $mailContent->getCode();

        $text = $mailContent->render(MailInterface::FORMAT_TEXT);

        $bodyText           = new MimePart($text);
        $bodyText->type     = Mime::TYPE_TEXT;
        $bodyText->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $bodyText->charset  = 'utf-8';

        $html = $mailContent->render(MailInterface::FORMAT_HTML);

        if ($this->layout) {
            $html = str_replace(self::LAYOUT_BODY, $html, $this->layout);

            if ($this->css) {
                $cssToInlineStyles = new CssToInlineStyles();

                $html = $cssToInlineStyles->convert(
                    $html,
                    $this->css
                );
            }
        }

        $bodyHtml           = new MimePart($html);
        $bodyHtml->type     = Mime::TYPE_HTML;
        $bodyHtml->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $bodyHtml->charset  = 'utf-8';

        $multipartContent = new MimeMessage();
        $multipartContent->setParts([$bodyText, $bodyHtml]);

        $multipartPart = new MimePart($multipartContent->generateMessage());
        $multipartPart->charset  = 'utf-8';
        $multipartPart->type     = 'multipart/alternative';
        $multipartPart->boundary = $multipartContent->getMime()->boundary();

        $this->content = new MimeMessage();
        $this->content->addPart($multipartPart);

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

    public function addImage(string $filename, string $path, string $type = 'image/png'): self
    {
        $image              = new MimePart(file_get_contents($path));
        $image->id          = $filename;
        $image->type        = $type;
        $image->filename    = $filename;
        $image->disposition = Mime::DISPOSITION_INLINE;
        $image->encoding    = Mime::ENCODING_BASE64;

        if ($this->content instanceof MimeMessage) {
            $this->content->addPart($image);
        }

        return $this;
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function setCss(string $css): self
    {
        $this->css = $css;

        return $this;
    }

    public function send(): void
    {
        $this->message->setBody($this->content);
        $this->message->setEncoding('UTF-8');

        $contentTypeHeader = $this->message->getHeaders()->get('Content-Type');

        if ($contentTypeHeader instanceof Header\ContentType) {
            $contentTypeHeader->setType('multipart/related');
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
