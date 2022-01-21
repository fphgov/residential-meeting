<?php

declare(strict_types=1);

namespace Mail;

use Laminas\Mail\Message;
use Laminas\Mime\Message as MimeMessage;
use Mail\Helper\MailContentHelperInterface;

interface MailAdapterInterface
{
    public function setTemplate(MailContentHelperInterface $mailContent): self;

    public function addPdfAttachment(string $filename, string $stream): self;

    public function addIcsAttachment(string $filename, string $stream): self;

    public function send(): void;

    public function getMessage(): Message;

    public function getContent(): MimeMessage;

    public function getName(): string;

    public function getMessageId(): string;

    public function clear(): void;
}
