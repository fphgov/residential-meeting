<?php

declare(strict_types=1);

return [
    'mezzio-authorization-acl' => [
        'roles'     => [
            'guest'     => [],
            'user'      => ['guest'],
            'editor'    => ['user'],
            'admin'     => ['user', 'editor'],
            'developer' => ['user', 'editor', 'admin'],
        ],
        'resources' => [
            'app.api.ping',
            'app.api.options.get',
            'app.api.account.check',
            'app.api.question.get',
        ],
        'allow'     => [
            'guest'     => [
                'app.api.ping',
                'app.api.options.get',
                'app.api.account.check',
                'app.api.vote',
            ],
            'user' => [
            ],
            'editor' => [
            ],
            'admin' => [
            ],
            'developer' => [

            ],
        ]
    ]
];
