<?php

declare(strict_types=1);

namespace App\Helper;

use Psr\Container\ContainerInterface;

final class MailContentRawHelperFactory
{
    public function __invoke(ContainerInterface $container): MailContentRawHelper
    {
        return new MailContentRawHelper();
    }
}
