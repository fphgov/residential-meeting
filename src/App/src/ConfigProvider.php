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
                Handler\Account\CheckHandler::class            => Handler\Account\CheckHandlerFactory::class,
                Handler\Account\ForgotCheckHandler::class      => Handler\Account\ForgotCheckHandlerFactory::class,
                Handler\Account\ForgotFirstHandler::class      => Handler\Account\ForgotFirstHandlerFactory::class,
                Handler\Account\ForgotSecondHandler::class     => Handler\Account\ForgotSecondHandlerFactory::class,
                Handler\Account\ForgotTokenCheckHandler::class => Handler\Account\ForgotTokenCheckHandlerFactory::class,
                Handler\Media\GetHandler::class                => Handler\Media\GetHandlerFactory::class,
                Handler\Vote\AddHandler::class                 => Handler\Vote\AddHandlerFactory::class,
                Handler\Setting\GetHandler::class              => Handler\Setting\GetHandlerFactory::class,
                Handler\Question\GetHandler::class             => Handler\Question\GetHandlerFactory::class,
                Handler\Question\GetAllHandler::class          => Handler\Question\GetAllHandlerFactory::class,
                Handler\Question\GetNavigationHandler::class   => Handler\Question\GetNavigationHandlerFactory::class,
                Handler\Stat\GetVoteHandler::class             => Handler\Stat\GetVoteHandlerFactory::class,
                Handler\Stat\GetHistoryHandler::class          => Handler\Stat\GetHistoryHandlerFactory::class,
                Model\VoteExportModel::class                   => Model\VoteExportModelFactory::class,
                Model\StatExportModel::class                   => Model\StatExportModelFactory::class,
                Service\AccountServiceInterface::class         => Service\AccountServiceFactory::class,
                Service\ForgotAccountServiceInterface::class   => Service\ForgotAccountServiceFactory::class,
                Service\MailQueueServiceInterface::class       => Service\MailQueueServiceFactory::class,
                Service\MediaServiceInterface::class           => Service\MediaServiceFactory::class,
                Service\UserServiceInterface::class            => Service\UserServiceFactory::class,
                Service\SettingServiceInterface::class         => Service\SettingServiceFactory::class,
                Service\VoteServiceInterface::class            => Service\VoteServiceFactory::class,
                Service\VoteValidationServiceInterface::class  => Service\VoteValidationServiceFactory::class,
                Service\MailServiceInterface::class            => Service\MailServiceFactory::class,
                Service\NewsletterServiceInterface::class      => Service\NewsletterServiceFactory::class,
                Helper\MailContentHelper::class                => Helper\MailContentHelperFactory::class,
                Helper\MailContentRawHelper::class             => Helper\MailContentRawHelperFactory::class,
            ],
        ];
    }

    public function getInputFilters(): array
    {
        return [
            'factories'  => [
                InputFilter\AccountCheckFilter::class             => InputFilter\AccountCheckFilterFactory::class,
                InputFilter\ForgotAccountSecondCheckFilter::class => InputFilter\ForgotAccountSecondCheckFilterFactory::class,
                InputFilter\ForgotDistrictCheckFilter::class      => InputFilter\ForgotDistrictCheckFilterFactory::class,
                InputFilter\ForgotTokenCheckFilter::class         => InputFilter\ForgotTokenCheckFilterFactory::class,
                InputFilter\VoteFilter::class                     => InputFilter\VoteFilterFactory::class,
            ],
            'invokables' => [
                InputFilter\ForgotAccountFirstCheckFilter::class => InputFilter\ForgotAccountFirstCheckFilter::class,
            ],
        ];
    }
}
