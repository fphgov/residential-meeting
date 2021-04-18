<?php

declare(strict_types=1);

return [
    'app' => [
        'municipality'        => getenv('APP_MUNICIPALITY'),
        'phone'               => getenv('APP_PHONE'),
        'url'                 => getenv('APP_URL'),
        'email'               => getenv('APP_EMAIL'),
        'notification'        => [
            'frequency' => (int)getenv('APP_NOTIFICATION_FREQUENCY'),
            'mail'      => [
                'testTo'   => getenv('APP_NOTIFICATION_MAIL_TESTTO'),
                'subject'  => getenv('APP_NOTIFICATION_MAIL_SUBJECT'),
                'replayTo' => getenv('APP_NOTIFICATION_MAIL_REPLAYTO'),
            ],
        ],
        'pagination' => [
            'maxPageSize' => getenv('APP_PAGINATION_MAX_PAGE_SIZE'),
        ]
    ],
];
