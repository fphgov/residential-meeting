<?php

declare(strict_types=1);

namespace Mail\Entity;

interface MailInterface
{
    public const FORMAT_TEXT = 'PlainText';
    public const FORMAT_HTML = 'Html';

    public function setCode(string $code): void;

    public function getCode(): string;

    public function setName(string $name): void;

    public function getName(): string;

    public function setPlainText(string $plainText): void;

    public function getPlainText(): string;

    public function setHtml(string $html): void;

    public function getHtml(): string;
}
