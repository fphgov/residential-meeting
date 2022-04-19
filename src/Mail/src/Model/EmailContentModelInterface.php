<?php

declare(strict_types=1);

namespace Mail\Model;

interface EmailContentModelInterface
{
    public function getSubject(): string;

    public function getHtml(): string;

    public function getPlainText(): string;
}
