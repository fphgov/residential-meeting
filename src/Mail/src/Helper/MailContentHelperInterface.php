<?php

declare(strict_types=1);

namespace Mail\Helper;

use Mail\Model\EmailTemplateRenderModelInterface;

interface MailContentHelperInterface extends EmailTemplateRenderModelInterface
{
    public function create(string $code, array $variables): self;

    public function setCode(string $code): void;
}
