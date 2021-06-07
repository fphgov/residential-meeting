<?php

declare(strict_types=1);

namespace App;

use Laminas\Hydrator;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Hal\Metadata\RouteBasedCollectionMetadata;
use Mezzio\Hal\Metadata\RouteBasedResourceMetadata;
use Mezzio\Hal\Metadata\MetadataMap;

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
            'dependencies'     => $this->getDependencies(),
            'input_filters'    => $this->getInputFilters(),
            MetadataMap::class => $this->getHalMetadataMap(),
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
            'factories'  => [
                Handler\Account\PasswordChangeHandler::class => Handler\Account\PasswordChangeHandlerFactory::class,
                Handler\Dashboard\GetHandler::class          => Handler\Dashboard\GetHandlerFactory::class,
                Handler\Dashboard\ChangeHandler::class       => Handler\Dashboard\ChangeHandlerFactory::class,
                Handler\User\ListHandler::class              => Handler\User\ListHandlerFactory::class,
                Handler\User\ActivateHandler::class          => Handler\User\ActivateHandlerFactory::class,
                Handler\User\ForgotAccountHandler::class     => Handler\User\ForgotAccountHandlerFactory::class,
                Handler\User\ForgotPasswordHandler::class    => Handler\User\ForgotPasswordHandlerFactory::class,
                Handler\User\ResetPasswordHandler::class     => Handler\User\ResetPasswordHandlerFactory::class,
                Handler\User\RegistrationHandler::class      => Handler\User\RegistrationHandlerFactory::class,
                Handler\User\VoteHandler::class              => Handler\User\VoteHandlerFactory::class,
                Handler\Project\ListAdminHandler::class      => Handler\Project\ListAdminHandlerFactory::class,
                Handler\Project\ListHandler::class           => Handler\Project\ListHandlerFactory::class,
                Handler\Project\GetHandler::class            => Handler\Project\GetHandlerFactory::class,
                Handler\Project\AddHandler::class            => Handler\Project\AddHandlerFactory::class,
                Handler\Project\StatisticsHandler::class     => Handler\Project\StatisticsHandlerFactory::class,
                Handler\Vote\AddHandler::class               => Handler\Vote\AddHandlerFactory::class,
                Handler\Setting\GetHandler::class            => Handler\Setting\GetHandlerFactory::class,
                Handler\Media\GetHandler::class              => Handler\Media\GetHandlerFactory::class,
                Handler\Media\DownloadHandler::class         => Handler\Media\DownloadHandlerFactory::class,
                Service\MailQueueServiceInterface::class     => Service\MailQueueServiceFactory::class,
                Service\MediaServiceInterface::class         => Service\MediaServiceFactory::class,
                Service\UserServiceInterface::class          => Service\UserServiceFactory::class,
                Service\ProjectServiceInterface::class       => Service\ProjectServiceFactory::class,
                Service\SettingServiceInterface::class       => Service\SettingServiceFactory::class,
                Service\VoteServiceInterface::class          => Service\VoteServiceFactory::class,
            ],
        ];
    }

    public function getInputFilters(): array
    {
        return [
            'factories' => [
                InputFilter\ProjectInputFilter::class     => InvokableFactory::class,
                InputFilter\UserRegistrationFilter::class => InputFilter\UserRegistrationFilterFactory::class,
                InputFilter\VoteFilter::class             => InputFilter\VoteFilterFactory::class,
                InputFilter\OfflineVoteFilter::class      => InputFilter\OfflineVoteFilterFactory::class,
            ],
        ];
    }

    public function getHalMetadataMap(): array
    {
        return [
            [
                '__class__'      => RouteBasedResourceMetadata::class,
                'resource_class' => Entity\Project::class,
                'route'          => 'app.api.project.show',
                'extractor'      => Hydrator\ClassMethodsHydrator::class,
            ],
            [
                '__class__'      => RouteBasedResourceMetadata::class,
                'resource_class' => Entity\ProjectListDTO::class,
                'route'          => 'app.api.project.show',
                'extractor'      => Hydrator\ClassMethodsHydrator::class,
            ],
            [
                '__class__'      => RouteBasedResourceMetadata::class,
                'resource_class' => Entity\ProjectStatisticsDTO::class,
                'route'          => 'app.api.project.show',
                'extractor'      => Hydrator\ClassMethodsHydrator::class,
            ],
            [
                '__class__'           => RouteBasedCollectionMetadata::class,
                'collection_class'    => Entity\ProjectCollection::class,
                'collection_relation' => 'projects',
                'route'               => 'app.api.project.list',
            ],
            [
                '__class__'           => RouteBasedCollectionMetadata::class,
                'collection_class'    => Entity\ProjectStatisticsCollection::class,
                'collection_relation' => 'projects',
                'route'               => 'app.api.project.statistics',
            ],
        ];
    }
}
