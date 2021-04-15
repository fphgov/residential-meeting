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
            'app.api.login',
            'app.api.user',
            'app.api.project.all',
            'app.api.project.get',

            'admin.api.login',
            'admin.api.cache.clear',
            'admin.api.dashboard.get',
            // 'admin.api.dashboard.set',
        ],
        'allow'     => [
            'guest'     => [
                'app.api.ping',
                'app.api.options.get',

                'app.api.project.all',
                'app.api.project.get',
            ],
            'user' => [
                'app.api.user',
            ],
            'editor' => [
                'admin.api.login',
            ],
            'admin' => [
                'admin.api.cache.clear',
                'admin.api.dashboard.get',
                // 'admin.api.dashboard.set',
            ],
            'developer' => [

            ],
        ]
    ]
];
