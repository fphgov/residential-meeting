<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Entity\Media;
use App\Service\MediaServiceInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DownloadHandler implements RequestHandlerInterface
{
    /** @var MediaServiceInterface */
    private $mediaService;

    public function __construct(
        MediaServiceInterface $mediaService
    ) {
        $this->mediaService = $mediaService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $media = $this->mediaService->getMedia($request->getAttribute('id'));

        if ($media === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $stream = $this->mediaService->getMediaStream($media);

        return new Response($stream, 200, [
            'Content-Type'              => $media->getType(),
            'Content-Disposition'       => 'attachment; filename="' . $media->getFilename() . '"',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Description'       => 'File Transfer',
            'Pragma'                    => 'public',
            'Expires'                   => '0',
            'Cache-Control'             => 'must-revalidate',
            'Content-Length'            => $stream->getSize(),
        ]);
    }
}
