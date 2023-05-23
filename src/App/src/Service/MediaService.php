<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use App\Entity\MediaInterface;
use DateTime;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\StreamInterface;

use function unlink;

final class MediaService implements MediaServiceInterface
{
    private $mediaRepository;

    public function __construct(
        private array $config,
        private EntityManagerInterface $em
    ) {
        $this->config          = $config;
        $this->em              = $em;
        $this->mediaRepository = $this->em->getRepository(Media::class);
    }

    public function process(): void
    {
        $medias = $this->mediaRepository->getExpiredMedias(new DateTime());

        foreach ($medias as $media) {
            $filePath = getenv('APP_UPLOAD') . '/' . $media->getFilename();

            unlink($filePath);

            $this->em->remove($media);
        }

        $this->em->flush();
    }

    public function getMedia(string $id): ?MediaInterface
    {
        return $this->mediaRepository->findOneBy(['id' => $id]);
    }

    public function getMediaStream(MediaInterface $media): StreamInterface
    {
        $filePath = getenv('APP_UPLOAD') . '/' . $media->getFilename();

        if ($media->getExpirationDate() === null) {
            $expiration = (new DateTime())->add(new DateInterval("PT24H"));

            $media->setExpirationDate($expiration);

            $this->em->flush();
        }

        return new Stream($filePath);
    }
}
