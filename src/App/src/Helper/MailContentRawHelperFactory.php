<?php

declare(strict_types=1);

namespace App\Helper;

use Psr\Container\ContainerInterface;

final class MailContentRawHelperFactory
{
    /**
     * @return MailContentRawHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        return new MailContentRawHelper();
    }
}
