<?php

declare(strict_types=1);

namespace App\Handler\Media;

use App\Service\MediaServiceInterface;
use Exception;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetHandler implements RequestHandlerInterface
{
    /** @var MediaServiceInterface */
    private $mediaService;

    /** @var Logger */
    private $audit;

    public function __construct(
        MediaServiceInterface $mediaService,
        Logger $audit
    ) {
        $this->mediaService = $mediaService;
        $this->audit        = $audit;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id    = $request->getAttribute('id');
        $media = $this->mediaService->getMedia($id);

        if ($media === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $mediaStream = null;

        try {
            $mediaStream = $this->mediaService->getMediaStream($media);
        } catch (Exception $e) {
            $this->audit->err('Not found media element', [
                'extra' => $e->getMessage() . ' | ' . $id,
            ]);

            return new Response('php://memory', 404);
        }

        return new Response($mediaStream);
    }
}
