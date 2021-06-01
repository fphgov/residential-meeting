<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/app/api/ping', App\Handler\PingHandler::class, 'app.api.ping');

    $app->get('/app/api/options', [
        App\Handler\Setting\GetHandler::class
    ], 'app.api.options.get');

    $app->post('/app/api/login', [
        Jwt\Handler\TokenHandler::class,
    ], 'app.api.login');

    if (getenv('NODE_ENV') === 'development') {
        $app->post('/app/api/user/registration', [
            App\Handler\User\RegistrationHandler::class
        ], 'app.api.user.registration');
    } else {
        $app->post('/app/api/user/registration', [
            \Middlewares\Recaptcha::class,
            App\Handler\User\RegistrationHandler::class
        ], 'app.api.user.registration');
    }

    $app->get('/app/api/user/activate/{hash}', [
        App\Handler\User\ActivateHandler::class
    ], 'app.api.user.activate');

    $app->post('/app/api/user/forgot/password', [
        App\Handler\User\ForgotPasswordHandler::class
    ], 'app.api.user.forgot.password');

    $app->post('/app/api/user/forgot/account', [
        App\Handler\User\ForgotAccountHandler::class
    ], 'app.api.user.forgot.account');

    $app->post('/app/api/user/reset/password', [
        App\Handler\User\ResetPasswordHandler::class
    ], 'app.api.user.reset.password');

    $app->get('/app/api/user', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\User\ListHandler::class
    ], 'app.api.user');

    $app->post('/app/api/user/vote', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\User\VoteHandler::class
    ], 'app.api.user.vote');

    $app->get('/app/api/projects', [
        App\Handler\Project\ListHandler::class
    ], 'app.api.project.list');

    $app->get('/app/api/statistics', [
        App\Handler\Project\StatisticsHandler::class
    ], 'app.api.project.statistics');

    $app->get('/app/api/projects/{id}', [
        App\Handler\Project\GetHandler::class
    ], 'app.api.project.show');

    $app->get('/app/api/media/{id}', [
        App\Handler\Media\GetHandler::class
    ], 'app.api.media.show');

    $app->get('/app/api/media/download/{id}', [
        App\Handler\Media\DownloadHandler::class
    ], 'app.api.media.download');

    // Admin
    if (getenv('NODE_ENV') === 'development') {
        $app->post('/admin/api/login', [
            Jwt\Handler\TokenHandler::class,
        ], 'admin.api.login');
    } else {
        $app->post('/admin/api/login', [
            \Middlewares\Recaptcha::class,
            Jwt\Handler\TokenHandler::class,
        ], 'admin.api.login');
    }

    if (getenv('NODE_ENV') === 'development') {
        $app->get('/admin/api/cache/clear', [
            App\Handler\Tools\ClearCacheHandler::class
        ], 'admin.api.cache.clear');
    } else {
        $app->get('/admin/api/cache/clear', [
            Jwt\Handler\JwtAuthMiddleware::class,
            App\Middleware\UserMiddleware::class,
            \Mezzio\Authorization\AuthorizationMiddleware::class,
            App\Handler\Tools\ClearCacheHandler::class
        ], 'admin.api.cache.clear');
    }

    $app->get('/admin/api/dashboard', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Dashboard\GetHandler::class
    ], 'admin.api.dashboard.get');

    $app->post('/admin/api/dashboard', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Dashboard\ChangeHandler::class
    ], 'admin.api.dashboard.set');

    $app->post('/admin/api/account/password', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Account\PasswordChangeHandler::class,
    ], 'admin.api.account.password.change');

    $app->post('/admin/api/project', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Project\AddHandler::class
    ], 'admin.api.project.add');

    $app->get('/admin/api/vote', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Project\ListAdminHandler::class
    ], 'admin.api.vote.list');

    $app->get('/admin/api/vote/stat', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Project\ListAdminHandler::class
    ], 'admin.api.vote.stat');

    $app->post('/admin/api/vote', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Vote\AddHandler::class
    ], 'admin.api.vote.add');
};
