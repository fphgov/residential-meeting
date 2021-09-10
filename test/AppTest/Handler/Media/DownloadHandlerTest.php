<?php

declare(strict_types=1);

namespace AppTest\Handler\Media;

use App\Handler\Media\DownloadHandler;
use App\Service\MediaServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use function json_decode;

class DownloadHandlerTest extends TestCase
{
    public function testNotFoundResponse()
    {
        $mediaService = $this->createMock(MediaServiceInterface::class);
        $mediaService->method('getMedia')->willReturn(null);

        $getMediaHandler = new DownloadHandler($mediaService);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturn("fake");

        $response = $getMediaHandler->handle($request);

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($response->getStatusCode(), 404);
    }
}
