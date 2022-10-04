<?php

declare(strict_types=1);

namespace Mail;

use Laminas\Mail\Message;
use Laminas\Mime\Message as MimeMessage;
use Mail\Model\EmailTemplateRenderModelInterface;

interface MailAdapterInterface
{
    public const LAYOUT_BODY = '[[body]]';

    public function setTemplate(EmailTemplateRenderModelInterface $mailContent): self;

    public function addPdfAttachment(string $filename, string $stream): self;

    public function addIcsAttachment(string $filename, string $stream): self;

    public function addImage(string $filename, string $path, string $type = 'image/png'): self;

    public function setLayout(string $layout): self;

    public function setCss(string $css): self;

    public function send(): void;

    public function getMessage(): Message;

    public function getContent(): MimeMessage;

    public function getName(): string;

    public function getMessageId(): string;

    public function clear(): void;
}
