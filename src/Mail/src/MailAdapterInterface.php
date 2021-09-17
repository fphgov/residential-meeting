<?php

declare(strict_types=1);

namespace Mail;

use Laminas\Mail\Message;
use Laminas\Mime\Message as MimeMessage;

interface MailAdapterInterface
{
    public function setTemplate(string $name, array $data): self;

    public function addPdfAttachment(string $filename, string $stream): self;

    public function addIcsAttachment(string $filename, string $stream): self;

    public function send(): void;

    public function getMessage(): Message;

    public function getContent(): MimeMessage;

    public function getName(): string;

    public function getMessageId(): string;

    public function clear(): void;
}
