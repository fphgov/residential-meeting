<?php

declare(strict_types=1);

use Mezzio\Authorization\Acl\LaminasAcl;
use Mezzio\Authorization\AuthorizationInterface;

return [
    'dependencies' => [
        'aliases' => [
            AuthorizationInterface::class  => LaminasAcl::class,
            'doctrine.entity_manager.orm_default' => \Doctrine\ORM\EntityManagerInterface::class,
        ],

        'invokables' => [

        ],
        'factories'  => [
            AuthorizationInterface::class => LaminasAclFactory::class,

            App\Listener\LoggingErrorListener::class    => App\Listener\LoggingErrorListenerFactory::class,
            Laminas\Db\Adapter\AdapterInterface::class  => Laminas\Db\Adapter\AdapterServiceFactory::class,
            \Doctrine\ORM\EntityManagerInterface::class => \Roave\PsrContainerDoctrine\EntityManagerFactory::class,

            App\Middleware\AuditMiddleware::class    => App\Middleware\AuditMiddlewareFactory::class,
            App\Middleware\UserMiddleware::class     => App\Middleware\UserMiddlewareFactory::class,
            App\Middleware\PhaseMiddleware::class    => App\Middleware\PhaseMiddlewareFactory::class,
            App\Middleware\CampaignMiddleware::class => App\Middleware\CampaignMiddlewareFactory::class,

            \Middlewares\Recaptcha::class => App\Middleware\RecaptchaMiddlewareFactory::class,

            // For tests
            App\InputFilter\IdeaInputFilter::class => App\InputFilter\IdeaInputFilterFactory::class,
        ],
        'delegators' => [
            Laminas\Stratigility\Middleware\ErrorHandler::class => [
                App\Listener\LoggingErrorListenerFactory::class,
            ],
        ],
    ],
];
