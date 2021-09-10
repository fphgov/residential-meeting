<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use App\Entity\MediaInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\StreamInterface;

final class MediaService implements MediaServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        array $config,
        EntityManagerInterface $em
    ) {
        $this->config = $config;
        $this->em     = $em;
    }

    public function getMedia(string $id): ?MediaInterface
    {
        $mediaRepository = $this->em->getRepository(Media::class);

        return $mediaRepository->findOneBy(['id' => $id]);
    }

    public function getMediaStream(MediaInterface $media): StreamInterface
    {
        $filePath = $this->config['app']['paths']['files'] . '/' . $media->getFilename();

        return new Stream($filePath);
    }
}
