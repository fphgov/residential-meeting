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
        $app->post('/app/api/account/check', [
            \Middlewares\Recaptcha::class,
            App\Handler\Account\CheckHandler::class
        ], 'app.api.account.check');
    }

    $app->get('/app/api/question/{id:\d+}', [
        App\Handler\Question\GetHandler::class
    ], 'app.api.question.get');

    $app->get('/app/api/question/all', [
        App\Handler\Question\GetAllHandler::class
    ], 'app.api.question.all');

    $app->get('/app/api/question/navigation', [
        App\Handler\Question\GetNavigationHandler::class
    ], 'app.api.question.navigation');

    $app->post('/app/api/vote', [
        App\Middleware\AccountMiddleware::class,
        App\Handler\Vote\AddHandler::class
    ], 'app.api.vote');

    $app->get('/app/api/stat/votes', [
        App\Middleware\StatisticsAccountMiddleware::class,
        App\Handler\Stat\GetVoteHandler::class
    ], 'app.api.stat.votes');

    $app->get('/app/api/stat/history', [
        App\Middleware\StatisticsAccountMiddleware::class,
        App\Handler\Stat\GetHistoryHandler::class
    ], 'app.api.stat.history');
};
