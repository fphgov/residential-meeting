<?php

declare(strict_types=1);

namespace Jwt\Handler;

use Tuupola\Middleware\JwtAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var JwtAuthentication $auth
     */
    private $auth;

    public function __construct(JwtAuthentication $auth)
    {
        $this->auth = $auth;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {
        return $this->auth->process($request, $handler);
    }
}
