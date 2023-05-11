<?php

declare(strict_types=1);

return [
    'app' => [
        'municipality'        => str_replace('"', '', getenv('APP_MUNICIPALITY')),
        'phone'               => str_replace('"', '', getenv('APP_PHONE')),
        'url'                 => str_replace('"', '', getenv('APP_URL')),
        'email'               => str_replace('"', '', getenv('APP_EMAIL')),
        'notification'        => [
            'frequency' => (int)str_replace(['"',"'"], "", getenv('APP_NOTIFICATION_FREQUENCY')),
            'mail'      => [
                'testTo'   => getenv('APP_NOTIFICATION_MAIL_TESTTO'),
                'subject'  => getenv('APP_NOTIFICATION_MAIL_SUBJECT'),
                'replayTo' => getenv('APP_NOTIFICATION_MAIL_REPLAYTO'),
            ],
            'force' => (string)getenv('APP_NOTIFICATION_FORCE') === "true",
        ],
        'newsletter'          => [
            'url'   => 'https://hirlevel.budapest.hu/subscr_api.php',
            'limit' => 20,
        ],
        'stat'                => [
            'token' => getenv('APP_STAT_TOKEN')
        ],
    ],
];
