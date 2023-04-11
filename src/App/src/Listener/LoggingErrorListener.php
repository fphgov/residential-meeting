<?php

declare(strict_types=1);

namespace App\Listener;

use Laminas\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class LoggingErrorListener implements LoggingErrorListenerInterface
{
    /**
     * Log message string with placeholders
     */
    public const LOG_STRING = '{status} [{method}] {uri}: {error} in {file} on line {line}';

    public function __construct(
        private LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function __invoke(
        Throwable $error,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): void {
        $this->logger->err(self::LOG_STRING, [
            'status' => $response->getStatusCode(),
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
            'error'  => $error->getMessage(),
            'file'   => $error->getFile(),
            'line'   => $error->getLine(),
        ]);
    }
}
