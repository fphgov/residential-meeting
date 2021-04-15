<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ApplicantInterface;

interface NoticeServiceInterface
{
    public function sendEmail(): void;

    public static function addHttp(string $url): string;
}
