<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies'  => $this->getDependencies(),
            'input_filters' => $this->getInputFilters(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'delegators' => [],
            'factories'  => [
                Handler\Account\CheckHandler::class           => Handler\Account\CheckHandlerFactory::class,
                Handler\Vote\AddHandler::class                => Handler\Vote\AddHandlerFactory::class,
                Handler\Setting\GetHandler::class             => Handler\Setting\GetHandlerFactory::class,
                Handler\Question\GetHandler::class            => Handler\Question\GetHandlerFactory::class,
                Handler\Question\GetAllHandler::class         => Handler\Question\GetAllHandlerFactory::class,
                Handler\Question\GetNavigationHandler::class  => Handler\Question\GetNavigationHandlerFactory::class,
                Service\AccountServiceInterface::class        => Service\AccountServiceFactory::class,
                Service\MailQueueServiceInterface::class      => Service\MailQueueServiceFactory::class,
                Service\UserServiceInterface::class           => Service\UserServiceFactory::class,
                Service\SettingServiceInterface::class        => Service\SettingServiceFactory::class,
                Service\VoteServiceInterface::class           => Service\VoteServiceFactory::class,
                Service\VoteValidationServiceInterface::class => Service\VoteValidationServiceFactory::class,
                Service\MailServiceInterface::class           => Service\MailServiceFactory::class,
                Helper\MailContentHelper::class               => Helper\MailContentHelperFactory::class,
                Helper\MailContentRawHelper::class            => Helper\MailContentRawHelperFactory::class,
            ],
        ];
    }

    public function getInputFilters(): array
    {
        return [
            'factories'  => [
                InputFilter\AccountCheckFilter::class => InputFilter\AccountCheckFilterFactory::class,
                InputFilter\VoteFilter::class         => InputFilter\VoteFilterFactory::class,
            ],
            'invokables' => [],
        ];
    }
}
