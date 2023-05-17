<?php

declare(strict_types=1);

return [
    'rabbitmq' => [
        'host'     => getenv('RABBITMQ_HOST'),
        'port'     =>(int)str_replace(['"', "'"], "", getenv('RABBITMQ_PORT')),
        'login'    => getenv('RABBITMQ_USERNAME'),
        'password' => getenv('RABBITMQ_PASSWORD'),
    ],
];
