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

    $app->get('/app/api/user/prize/{hash}', [
        App\Handler\User\PrizeHandler::class
    ], 'app.api.user.prize');

    $app->post('/app/api/user/forgot/password', [
        App\Handler\User\ForgotPasswordHandler::class
    ], 'app.api.user.forgot.password');

    $app->post('/app/api/user/reset/password', [
        App\Handler\User\ResetPasswordHandler::class
    ], 'app.api.user.reset.password');

    $app->get('/app/api/user', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\User\GetHandler::class
    ], 'app.api.user');

    $app->post('/app/api/user/idea', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\User\IdeaHandler::class
    ], 'app.api.user.idea');

    $app->post('/app/api/user/vote', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\User\VoteHandler::class
    ], 'app.api.user.vote');

    $app->post('/app/api/user/password', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\Account\PasswordChangeHandler::class,
    ], 'app.api.account.password.change');

    $app->delete('/app/api/user/delete', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        App\Handler\Account\DeleteHandler::class,
    ], 'app.api.account.delete');

    $app->get('/app/api/projects', [
        App\Handler\Project\ListHandler::class
    ], 'app.api.project.list');

    $app->get('/app/api/projects/{id:\d+}', [
        App\Handler\Project\GetHandler::class
    ], 'app.api.project.show');

    $app->get('/app/api/projects/filter', [
        App\Handler\Project\FilterHandler::class
    ], 'app.api.project.filter');

    $app->get('/app/api/ideas', [
        App\Handler\Idea\ListHandler::class
    ], 'app.api.idea.list');

    $app->get('/app/api/ideas/{id\d+}', [
        App\Handler\Idea\GetHandler::class
    ], 'app.api.idea.show');

    $app->get('/app/api/idea/filter', [
        App\Handler\Idea\FilterHandler::class
    ], 'app.api.idea.filter');

    $app->get('/app/api/statistics', [
        App\Handler\Project\StatisticsHandler::class
    ], 'app.api.project.statistics');

    $app->get('/app/api/media/{id}', [
        App\Handler\Media\GetHandler::class
    ], 'app.api.media.show');

    $app->get('/app/api/media/download/{id}', [
        App\Handler\Media\DownloadHandler::class
    ], 'app.api.media.download');

    $app->get('/app/api/page/{slug}', [
        App\Handler\Page\GetHandler::class
    ], 'app.api.page.show');

    $app->get('/app/api/post', [
        App\Handler\Post\GetAllHandler::class
    ], 'app.api.post.all');

    $app->get('/app/api/post/{slug}', [
        App\Handler\Post\GetHandler::class
    ], 'app.api.post.show');

    $app->post('/app/api/geocoding', [
        App\Handler\Tools\GetAddressHandler::class
    ], 'app.api.geocoding');

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
