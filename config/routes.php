<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/app/api/ping', App\Handler\PingHandler::class, 'api.ping');

    $app->post('/app/api/login', [
        Jwt\Handler\TokenHandler::class,
    ], 'api.login');

    $app->get('/app/api/user', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\User\ListHandler::class
    ], 'api.user');

    $app->get('/app/api/project/{hashId}', [
        App\Handler\Project\GetHandler::class
    ], 'api.project.get');

    $app->post('/app/api/project', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\Project\AddHandler::class
    ], 'api.project.add');

    $app->get('/app/api/project', [
        App\Handler\Project\ListHandler::class
    ], 'api.project.all');
};
