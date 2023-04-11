<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\AccountServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountServiceInterface $accountService
    ) {
        $this->accountService = $accountService;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $authCode = $request->getHeaderLine('Authorization');

        try {
            $account = $this->accountService->getAccount($authCode);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'No authentication',
            ], 401);
        }

        return $handler->handle(
            $request->withAttribute(self::class, $account)
        );
    }
}
