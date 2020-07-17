<?php

declare(strict_types=1);

use App\Middleware\CorsMiddlewareFactory;
use Tuupola\Middleware\CorsMiddleware;

return [
    'cors' => [
        // "origin" => ["http://localhost"],
        "origin" => ["*"],
        "methods" => ["GET", "POST", "PUT"],
        "headers.allow" => ["Authorization", "Content-Type", "Accept", "X-Requested-With", "Origin", "Referer", "User-Agent"],
        "headers.expose" => [],
        "credentials" => false,
        "cache" => 0,
    ],
    'dependencies' => [
        'factories'  => [
            CorsMiddleware::class => CorsMiddlewareFactory::class,
        ]
    ],
];
