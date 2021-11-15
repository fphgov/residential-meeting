<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

chdir(__DIR__ . '/../../');

use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Postal\Parser;
use FphGov\Arcgis\Service\ArcgisServiceInterface;

if (! extension_loaded('postal')) {
    dl('postal');
}

if (! extension_loaded('openswoole')) {
    dl('openswoole');
}

$server = new Swoole\HTTP\Server("0.0.0.0", 9501);

$server->on("start", function (Swoole\Http\Server $server) {
    echo "Postal http server is started at http://0.0.0.0:9501\n";
});

$server->on("request", function (Swoole\Http\Request $request, Swoole\Http\Response $response) {
    require 'vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__, 2));
    $dotenv->load();

    $container = require 'config/container.php';

    $acrgisService = $container->get(ArcgisServiceInterface::class);

    $body = $request->getContent();

    $response->header('Cache-Control', 'no-cache');

    parse_str($body, $parsedBody);

    if (empty($parsedBody) || ! isset($parsedBody['address'])) {
        $response->header("Content-Type", "application/json");
        $response->status(204);

        return $response->end();
    }

    $results = $acrgisService->findAddress($parsedBody['address']);

    $response->header("Content-Type", "application/json");
    $response->end(json_encode($results));
});

$server->on("shutdown", function ($server) {
    echo "Server is shutting down.\n";
});

$server->start();
