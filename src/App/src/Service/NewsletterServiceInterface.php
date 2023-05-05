<?php

declare(strict_types=1);

namespace App\Service;

interface NewsletterServiceInterface
{
    public function process(string $cid): void;
}
