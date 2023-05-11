<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\AccountServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StatisticsAccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private array $config,
        private AccountServiceInterface $accountService
    ) {
        $this->accountService = $accountService;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $authCode = $request->getHeaderLine('Authorization');

        if ($authCode !== $this->config['app']['stat']['token']) {
            return new JsonResponse([
                'message' => 'No authentication',
            ], 401);
        }

        return $handler->handle($request);
    }
}
