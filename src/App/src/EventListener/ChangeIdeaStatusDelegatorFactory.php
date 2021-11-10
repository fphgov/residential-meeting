<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;

use function call_user_func;

class ChangeIdeaStatusDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        $subscriber = call_user_func($callback);

        return new ChangeIdeaStatusDelegator($container, $subscriber);
    }
}
