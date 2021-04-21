<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Stream;

use function mime_content_type;

final class MediaService implements MediaServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function getMedia(string $id)
    {
        $mediaRepository = $this->em->getRepository(Media::class);

        $media = $mediaRepository->findOneBy(['id' => $id]);

        if ($media === null) {
            return null;
        }

        return $media;
    }

    public function getMediaInfo(string $id): array
    {
        $mediaRepository = $this->em->getRepository(Media::class);

        $media = $mediaRepository->findOneBy(['id' => $id]);

        if ($media === null) {
            return null;
        }

        $mime = mime_content_type($media->getFile());

        return [
            'media' => $media,
            'mime'  => $mime,
        ];
    }
}
