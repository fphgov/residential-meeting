<?php

declare(strict_types=1);

namespace Mail\Helper;

use Mail\Model\EmailContentModelInterface;
use Mail\Model\EmailTemplateRenderModelInterface;

interface MailContentRawHelperInterface extends EmailTemplateRenderModelInterface
{
    public const MAIL_CODE = 'custom';

    public function create(EmailContentModelInterface $emailContentModel, array $variables): self;
}
