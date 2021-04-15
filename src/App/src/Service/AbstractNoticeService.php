<?php

declare(strict_types=1);

namespace App\Service;

abstract class AbstractNoticeService
{
    public static function addHttp(string $url): string
    {
        if (! preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }
}
