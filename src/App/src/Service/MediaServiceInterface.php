<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use App\Entity\MediaInterface;
use Psr\Http\Message\StreamInterface;

interface MediaServiceInterface
{
    public function getMedia(string $id): ?MediaInterface;

    public function getMediaStream(MediaInterface $media): StreamInterface;
}
