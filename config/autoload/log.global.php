<?php

declare(strict_types=1);

return [
    'log'    => [
        'writers'    => [
            'stdout' => [
                'name'     => 'stream',
                'priority' => Laminas\Log\Logger::ALERT,
                'options'  => [
                    'stream'    => 'php://stdout',
                    'formatter' => [
                        'name'    => Laminas\Log\Formatter\Simple::class,
                    ],
                    'filters'   => [
                        'priority' => [
                            'name'    => 'priority',
                            'options' => [
                                'operator' => '<=',
                                'priority' => Laminas\Log\Logger::INFO,
                            ],
                        ],
                    ],
                ],
            ],
            'db'     => [
                'name'     => 'db',
                'priority' => Laminas\Log\Logger::ALERT,
                'options'  => [
                    'db'        => new Laminas\Db\Adapter\Adapter([
                        'driver'   => getenv('DB_DRIVER'),
                        'database' => getenv('DB_DATABASE'),
                        'host'     => getenv('DB_HOSTNAME'),
                        'username' => getenv('DB_USER'),
                        'password' => getenv('DB_PASSWORD'),
                        'port'     => getenv('DB_PORT'),
                    ]),
                    'table'     => 'log_error',
                    'column'    => [
                        'timestamp'    => 'timestamp',
                        'priority'     => 'priority',
                        'priorityName' => 'priorityName',
                        'message'      => 'message',
                    ],
                    'formatter' => [
                        'name'    => Laminas\Log\Formatter\Db::class,
                        'options' => [
                            'dateTimeFormat' => 'Y-m-d h:i:s',
                        ],
                    ],
                    'filters'   => [
                        'priority' => [
                            'name'    => 'priority',
                            'options' => [
                                'operator' => '<=',
                                'priority' => Laminas\Log\Logger::INFO,
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'processors' => [
            'requestid' => [
                'name' => Laminas\Log\Processor\RequestId::class,
            ],
        ],
    ],
    'logger' => [
        'AuditLogger' => [
            'writers'    => [
                'db'     => [
                    'name'     => 'db',
                    'priority' => Laminas\Log\Logger::ALERT,
                    'options'  => [
                        'db'        => new Laminas\Db\Adapter\Adapter([
                            'driver'   => getenv('DB_DRIVER'),
                            'database' => getenv('DB_DATABASE'),
                            'host'     => getenv('DB_HOSTNAME'),
                            'username' => getenv('DB_USER'),
                            'password' => getenv('DB_PASSWORD'),
                            'port'     => getenv('DB_PORT'),
                        ]),
                        'table'     => 'log_audit',
                        'column'    => [
                            'timestamp'    => 'timestamp',
                            'priority'     => 'priority',
                            'priorityName' => 'priorityName',
                            'message'      => 'message',
                            'extra'        => [
                                'extra' => 'extra',
                            ],
                        ],
                        'formatter' => [
                            'name'    => Laminas\Log\Formatter\Db::class,
                            'options' => [
                                'dateTimeFormat' => 'Y-m-d h:i:s',
                            ],
                        ],
                        'filters'   => [
                            'priority' => [
                                'name'    => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => Laminas\Log\Logger::INFO,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => Laminas\Log\Processor\RequestId::class,
                ],
            ],
        ],
    ],
];
