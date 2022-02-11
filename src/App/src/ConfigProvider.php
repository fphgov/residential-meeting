<?php

declare(strict_types=1);

namespace App;

use Laminas\Hydrator;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Hal\Metadata\MetadataMap;
use Mezzio\Hal\Metadata\RouteBasedCollectionMetadata;
use Mezzio\Hal\Metadata\RouteBasedResourceMetadata;

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
                Handler\PingHandler::class                  => Handler\PingHandler::class,
                Service\PostalClientServiceInterface::class => Service\PostalClientService::class,
                EventListener\ChangeIdeaStatus::class       => EventListener\ChangeIdeaStatus::class,
            ],
            'delegators' => [
                EventListener\ChangeIdeaStatus::class => [
                    EventListener\ChangeIdeaStatusDelegatorFactory::class,
                ],
            ],
            'factories'  => [
                Handler\Account\PasswordChangeHandler::class => Handler\Account\PasswordChangeHandlerFactory::class,
                Handler\Account\DeleteHandler::class         => Handler\Account\DeleteHandlerFactory::class,
                Handler\Dashboard\GetHandler::class          => Handler\Dashboard\GetHandlerFactory::class,
                Handler\Dashboard\ChangeHandler::class       => Handler\Dashboard\ChangeHandlerFactory::class,
                Handler\User\ListHandler::class              => Handler\User\ListHandlerFactory::class,
                Handler\User\GetHandler::class               => Handler\User\GetHandlerFactory::class,
                Handler\User\ActivateHandler::class          => Handler\User\ActivateHandlerFactory::class,
                Handler\User\ForgotPasswordHandler::class    => Handler\User\ForgotPasswordHandlerFactory::class,
                Handler\User\ResetPasswordHandler::class     => Handler\User\ResetPasswordHandlerFactory::class,
                Handler\User\RegistrationHandler::class      => Handler\User\RegistrationHandlerFactory::class,
                Handler\User\VoteHandler::class              => Handler\User\VoteHandlerFactory::class,
                Handler\User\IdeaHandler::class              => Handler\User\IdeaHandlerFactory::class,
                Handler\User\PrizeHandler::class             => Handler\User\PrizeHandlerFactory::class,
                Handler\Page\GetHandler::class               => Handler\Page\GetHandlerFactory::class,
                Handler\Post\GetHandler::class               => Handler\Post\GetHandlerFactory::class,
                Handler\Post\GetAllHandler::class            => Handler\Post\GetAllHandlerFactory::class,
                Handler\Post\AdminListHandler::class         => Handler\Post\AdminListHandlerFactory::class,
                Handler\Post\AdminGetHandler::class          => Handler\Post\AdminGetHandlerFactory::class,
                Handler\Post\AdminModifyHandler::class       => Handler\Post\AdminModifyHandlerFactory::class,
                Handler\Post\AdminAddHandler::class          => Handler\Post\AdminAddHandlerFactory::class,
                Handler\Post\AdminDeleteHandler::class       => Handler\Post\AdminDeleteHandlerFactory::class,
                Handler\Post\GetStatusHandler::class         => Handler\Post\GetStatusHandlerFactory::class,
                Handler\Post\GetCategoryHandler::class       => Handler\Post\GetCategoryHandlerFactory::class,
                Handler\Idea\GetHandler::class               => Handler\Idea\GetHandlerFactory::class,
                Handler\Idea\ListHandler::class              => Handler\Idea\ListHandlerFactory::class,
                Handler\Idea\FilterHandler::class            => Handler\Idea\FilterHandlerFactory::class,
                Handler\Idea\AdminListHandler::class         => Handler\Idea\AdminListHandlerFactory::class,
                Handler\Idea\AdminGetHandler::class          => Handler\Idea\AdminGetHandlerFactory::class,
                Handler\Idea\AdminModifyHandler::class       => Handler\Idea\AdminModifyHandlerFactory::class,
                Handler\Idea\ExportHandler::class            => Handler\Idea\ExportHandlerFactory::class,
                Handler\Project\AdminListHandler::class      => Handler\Project\AdminListHandlerFactory::class,
                Handler\Project\AdminGetHandler::class       => Handler\Project\AdminGetHandlerFactory::class,
                Handler\Project\AdminModifyHandler::class    => Handler\Project\AdminModifyHandlerFactory::class,
                Handler\Project\ListAdminHandler::class      => Handler\Project\ListAdminHandlerFactory::class,
                Handler\Project\ListHandler::class           => Handler\Project\ListHandlerFactory::class,
                Handler\Project\GetHandler::class            => Handler\Project\GetHandlerFactory::class,
                Handler\Project\AddHandler::class            => Handler\Project\AddHandlerFactory::class,
                Handler\Project\StatisticsHandler::class     => Handler\Project\StatisticsHandlerFactory::class,
                Handler\Project\FilterHandler::class         => Handler\Project\FilterHandlerFactory::class,
                Handler\Vote\AddHandler::class               => Handler\Vote\AddHandlerFactory::class,
                Handler\Setting\GetHandler::class            => Handler\Setting\GetHandlerFactory::class,
                Handler\Media\GetHandler::class              => Handler\Media\GetHandlerFactory::class,
                Handler\Media\DownloadHandler::class         => Handler\Media\DownloadHandlerFactory::class,
                Handler\Tools\GetAddressHandler::class       => Handler\Tools\GetAddressHandlerFactory::class,
                Handler\Workflow\GetStatesHandler::class     => Handler\Workflow\GetStatesHandlerFactory::class,
                Handler\Workflow\GetExtrasHandler::class     => Handler\Workflow\GetExtrasHandlerFactory::class,
                Handler\Mail\AdminListHandler::class         => Handler\Mail\AdminListHandlerFactory::class,
                Handler\Mail\AdminGetHandler::class          => Handler\Mail\AdminGetHandlerFactory::class,
                Handler\Mail\AdminModifyHandler::class       => Handler\Mail\AdminModifyHandlerFactory::class,
                Service\MailQueueServiceInterface::class     => Service\MailQueueServiceFactory::class,
                Service\MediaServiceInterface::class         => Service\MediaServiceFactory::class,
                Service\UserServiceInterface::class          => Service\UserServiceFactory::class,
                Service\ProjectServiceInterface::class       => Service\ProjectServiceFactory::class,
                Service\PostServiceInterface::class          => Service\PostServiceFactory::class,
                Service\SettingServiceInterface::class       => Service\SettingServiceFactory::class,
                Service\VoteServiceInterface::class          => Service\VoteServiceFactory::class,
                Service\IdeaServiceInterface::class          => Service\IdeaServiceFactory::class,
                Service\PhaseServiceInterface::class         => Service\PhaseServiceFactory::class,
                Service\MailServiceInterface::class          => Service\MailServiceFactory::class,
                Model\IdeaExportModel::class                 => Model\IdeaExportModelFactory::class,
                Helper\MailContentHelper::class              => Helper\MailContentHelperFactory::class,
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
                InputFilter\IdeaInputFilter::class        => InputFilter\IdeaInputFilterFactory::class,
                InputFilter\OfflineVoteFilter::class      => InputFilter\OfflineVoteFilterFactory::class,
                InputFilter\PostInputFilter::class        => InputFilter\PostInputFilterFactory::class,
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
                'resource_class' => Entity\Idea::class,
                'route'          => 'app.api.idea.show',
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
                'resource_class' => Entity\IdeaListDTO::class,
                'route'          => 'app.api.idea.list',
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
                'collection_class'    => Entity\IdeaCollection::class,
                'collection_relation' => 'ideas',
                'route'               => 'app.api.idea.list',
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
