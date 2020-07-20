<?php

declare(strict_types=1);

namespace App;

use Laminas\ServiceManager\Factory\InvokableFactory;

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
            'input_filters' => $this->getInputFilters()
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
                Handler\User\ListHandler::class    => Handler\User\ListHandlerFactory::class,
                Handler\Project\ListHandler::class => Handler\Project\ListHandlerFactory::class,
                Handler\Project\GetHandler::class  => Handler\Project\GetHandlerFactory::class,
                Handler\Project\AddHandler::class  => Handler\Project\AddHandlerFactory::class,
                
                Service\UserServiceInterface::class => Service\UserServiceFactory::class,
                Service\ProjectServiceInterface::class => Service\ProjectServiceFactory::class,
            ],
        ];
    }

    public function getInputFilters(): array
    {
        return [
            'factories' => [
                InputFilter\ProjectInputFilter::class => InvokableFactory::class,
            ],
        ];
    }
}
