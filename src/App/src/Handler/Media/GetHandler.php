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

final class GetHandler implements RequestHandlerInterface
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

        return new Response(
            $this->mediaService->getMediaStream($media)
        );
    }
}
