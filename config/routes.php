<?php

declare(strict_types=1);

use Mezzio\Application;

return static function (
    Application $app,
): void {
    $app->get('/app/api/ping', App\Handler\PingHandler::class, 'app.api.ping');

    $app->get('/app/api/options', [
        App\Handler\Setting\GetHandler::class
    ], 'app.api.options.get');

    if (getenv('NODE_ENV') === 'development') {
        $app->post('/app/api/account/check', [
            App\Handler\Account\CheckHandler::class
        ], 'app.api.account.check');
    } else {
        $app->post('/app/api/user/check', [
            \Middlewares\Recaptcha::class,
            App\Handler\account\CheckHandler::class
        ], 'app.api.account.check');
    }

    $app->get('/app/api/question', [
        App\Middleware\AccountMiddleware::class,
        App\Handler\Question\GetHandler::class
    ], 'app.api.question.get');

    $app->post('/app/api/vote', [
        App\Middleware\AccountMiddleware::class,
        App\Handler\Vote\AddHandler::class
    ], 'app.api.vote');
};
