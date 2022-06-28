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
        App\Middleware\CampaignMiddleware::class,
        App\Handler\User\VoteHandler::class
    ], 'app.api.user.vote');

    $app->get('/app/api/vote/list', [
        App\Handler\Vote\ListHandler::class
    ], 'app.api.vote.list');

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

    $app->get('/app/api/plans', [
        App\Handler\Plan\ListHandler::class
    ], 'app.api.plans.list');

    $app->get('/app/api/plans/{id:\d+}', [
        App\Handler\Plan\GetHandler::class
    ], 'app.api.plans.show');

    $app->get('/app/api/plans/filter', [
        App\Handler\Plan\FilterHandler::class
    ], 'app.api.plan.filter');

    $app->get('/app/api/ideas', [
        App\Handler\Idea\ListHandler::class
    ], 'app.api.idea.list');

    $app->get('/app/api/ideas/{id:\d+}', [
        App\Handler\Idea\GetHandler::class
    ], 'app.api.idea.show');

    $app->get('/app/api/idea/filter', [
        App\Handler\Idea\FilterHandler::class
    ], 'app.api.idea.filter');

    $app->get('/app/api/statistics', [
        App\Handler\Project\StatisticsHandler::class
    ], 'app.api.project.statistics');

    $app->get('/app/api/media/{id:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', [
        App\Handler\Media\GetHandler::class
    ], 'app.api.media.show');

    $app->get('/app/api/media/download/{id:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}}', [
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

    $app->get('/app/api/phase/check', [
        App\Handler\Phase\CheckHandler::class
    ], 'app.api.phase.check');

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

    $app->post('/admin/api/posts', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\AdminListHandler::class
    ], 'admin.api.post.list');

    $app->get('/admin/api/posts/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\AdminGetHandler::class
    ], 'admin.api.post.get');

    $app->post('/admin/api/posts/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\AdminModifyHandler::class
    ], 'admin.api.post.modify');

    $app->post('/admin/api/posts/new', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\AdminAddHandler::class
    ], 'admin.api.post.new');

    $app->delete('/admin/api/posts/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\AdminDeleteHandler::class
    ], 'admin.api.post.delete');

    $app->get('/admin/api/post/status', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\GetStatusHandler::class
    ], 'admin.api.post.status.list');

    $app->get('/admin/api/post/category', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Post\GetCategoryHandler::class
    ], 'admin.api.post.category.list');

    $app->post('/admin/api/ideas', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Idea\AdminListHandler::class
    ], 'admin.api.idea.list');

    $app->get('/admin/api/ideas/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Idea\AdminGetHandler::class
    ], 'admin.api.idea.get');

    $app->post('/admin/api/ideas/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Idea\AdminModifyHandler::class
    ], 'admin.api.idea.modify');

    $app->get('/admin/api/ideas/export', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Idea\ExportHandler::class
    ], 'admin.api.idea.export');

    $app->post('/admin/api/ideas/answer/import', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Idea\AdminImportAnswerHandler::class
    ], 'admin.api.idea.answer.import');

    $app->post('/admin/api/ideas/email/import', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Idea\AdminSendEmailHandler::class
    ], 'admin.api.idea.email.import');

    $app->post('/admin/api/projects', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Project\AdminListHandler::class
    ], 'admin.api.project.list');

    $app->get('/admin/api/projects/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Project\AdminGetHandler::class
    ], 'admin.api.project.get');

    $app->post('/admin/api/projects/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Project\AdminModifyHandler::class
    ], 'admin.api.project.modify');

    $app->get('/admin/api/implementations', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Implementation\ListHandler::class,
    ], 'admin.api.implementation.list');

    $app->post('/admin/api/implementations', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Implementation\AddHandler::class,
    ], 'admin.api.implementation.add');

    $app->post('/admin/api/implementations/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Implementation\ModifyHandler::class,
    ], 'admin.api.implementation.modify');

    $app->delete('/admin/api/implementations/delete/{id:\d+}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Implementation\DeleteHandler::class,
    ], 'admin.api.implementation.delete');

    $app->get('/admin/api/workflow/states', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Workflow\GetStatesHandler::class
    ], 'admin.api.workflow.states.list');

    $app->get('/admin/api/workflow/extras', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Workflow\GetExtrasHandler::class
    ], 'admin.api.workflow.extras.list');

    $app->get('/admin/api/emails', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Mail\AdminListHandler::class
    ], 'admin.api.email.list');

    $app->get('/admin/api/emails/{code:.*}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Mail\AdminGetHandler::class
    ], 'admin.api.email.get');

    $app->post('/admin/api/emails/{code:.*}', [
        Jwt\Handler\JwtAuthMiddleware::class,
        App\Middleware\UserMiddleware::class,
        \Mezzio\Authorization\AuthorizationMiddleware::class,
        App\Handler\Mail\AdminModifyHandler::class
    ], 'admin.api.email.modify');
};
