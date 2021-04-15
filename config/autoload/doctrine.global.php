<?php

declare(strict_types=1);

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url'           => 'mysql://'. getenv('DB_USER') .':'. getenv('DB_PASSWORD') .'@'. getenv('DB_HOSTNAME') . '/' . getenv('DB_DATABASE'),
                    'charset'       => getenv('DB_CHARSET'),
                    'configuration' => []
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'App\Entity' => 'my_entity',
                ],
            ],
            'my_entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../../src/App/src/Entity'],
            ],
        ],
    ]
];
