<?php

declare(strict_types=1);

namespace Jwt\Handler;

use Doctrine\ORM\EntityManagerInterface;
// use Lcobucci\Jose\Parsing\Parser;
use Lcobucci\JWT\Builder;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\AuthenticationInterface;
use Zend\Expressive\Authentication\Exception;

class TokenHandlerFactory
{
    public function __invoke(ContainerInterface $container): TokenHandler
    {
        $config = $container->has('config') ? $container->get('config') : [];

        if (! isset($config['jwt'])) {
            throw new Exception('Missing JWT configuration');
        }

        // $builder = new Builder(new Parser());
        $builder = new Builder();

        return new TokenHandler(
            $container->get(EntityManagerInterface::class),
            $builder,
            $config['jwt']
        );
    }
}
