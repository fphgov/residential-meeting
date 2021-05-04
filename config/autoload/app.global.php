<?php

declare(strict_types=1);

return [
    'app' => [
        'municipality'        => str_replace('"', '', getenv('APP_MUNICIPALITY')),
        'phone'               => str_replace('"', '', getenv('APP_PHONE')),
        'url'                 => str_replace('"', '', getenv('APP_URL')),
        'email'               => str_replace('"', '', getenv('APP_EMAIL')),
        'notification'        => [
            'frequency' => (int)getenv('APP_NOTIFICATION_FREQUENCY'),
            'mail'      => [
                'testTo'   => getenv('APP_NOTIFICATION_MAIL_TESTTO'),
                'subject'  => getenv('APP_NOTIFICATION_MAIL_SUBJECT'),
                'replayTo' => getenv('APP_NOTIFICATION_MAIL_REPLAYTO'),
            ],
        ],
        'pagination' => [
            'maxPageSize' => (int)getenv('APP_PAGINATION_MAX_PAGE_SIZE'),
        ]
    ],
];
