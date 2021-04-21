<?php

declare(strict_types=1);

namespace App\Service;

interface MediaServiceInterface
{
    public function getMedia(string $id);

    public function getMediaInfo(string $id): array;
}
