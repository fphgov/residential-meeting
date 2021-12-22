<?php
// phpcs:ignoreFile
declare(strict_types=1);

namespace App\EventListener;

use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Psr\Container\ContainerInterface;

use function call_user_func;

class ChangeIdeaStatusDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, ?array $options = null)
    {
        $subscriber = call_user_func($callback);

        return new ChangeIdeaStatusDelegator($container, $subscriber);
    }
}
