<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\PhaseServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PhaseMiddleware implements MiddlewareInterface
{
    /** @var PhaseServiceInterface */
    private $phaseService;

    public function __construct(PhaseServiceInterface $phaseService)
    {
        $this->phaseService = $phaseService;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $phase = $this->phaseService->getRepository()->getCurrentPhase();

        return $handler->handle(
            $request->withAttribute(self::class, $phase)
        );
    }
}
