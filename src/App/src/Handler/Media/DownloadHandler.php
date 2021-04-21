<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Service\MediaServiceInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DownloadHandler implements RequestHandlerInterface
{
    /** @var MediaServiceInterface */
    protected $mediaService;

    public function __construct(
        MediaServiceInterface $mediaService
    ) {
        $this->mediaService = $mediaService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $mediaInfo = $this->mediaService->getMediaInfo($request->getAttribute('id'));

        if ($mediaInfo === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $mime  = $mediaInfo['mime'];
        $media = $mediaInfo['media'];

        $stream = new Stream($media->getFile());

        return new Response($stream, 200, [
            'Content-Type'              => $mime,
            'Content-Disposition'       => 'attachment; filename="'. $media->getFilename() .'"',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Description'       => 'File Transfer',
            'Pragma'                    => 'public',
            'Expires'                   => '0',
            'Cache-Control'             => 'must-revalidate',
            'Content-Length'            => $stream->getSize(),
        ]);
    }
}
