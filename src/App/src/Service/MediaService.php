<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use App\Entity\MediaInterface;
use DateTime;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Stream;
use Imagick;
use Exception;
use Psr\Http\Message\StreamInterface;

use function unlink;
use function pathinfo;
use function fopen;

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
        $filePath = $this->checkImage($media);

        if ($media->getExpirationDate() === null) {
            $expiration = (new DateTime())->add(new DateInterval("PT24H"));

            $media->setExpirationDate($expiration);

            $this->em->flush();
        }

        return new Stream($filePath);
    }

    private function checkImage(MediaInterface $media): string
    {
        $filePath = getenv('APP_UPLOAD') . '/' . $media->getFilename();

        $uploadedImage = fopen($filePath, 'rb');

        $image = new Imagick();
        $image->readImageFile($uploadedImage);

        if (! $image->valid()) {
            throw new Exception('Image not valid');
        }

        return $this->normalizeImage($image, $media, $filePath);
    }

    private function normalizeImage(
        Imagick $image,
        MediaInterface $media,
        string $filePath
    ): string
    {
        $filename = pathinfo($filePath, PATHINFO_FILENAME);

        $mimeType = $image->getImageMimeType();

        if (in_array($mimeType, ["image/avif", "image/heic"])) {
            $newImageBasename = $filename . '.jpeg';
            $newImagePath     = getenv('APP_UPLOAD') . '/' . $newImageBasename;

            $image->setImageFormat('jpeg');
            $image->writeImage($newImagePath);
            $image->clear();
            $image->destroy();

            $media->setFilename($newImageBasename);
            $media->setType('image/jpeg');

            unlink($filePath);

            $this->em->flush();

            return $newImagePath;
        }

        return $filePath;
    }
}
