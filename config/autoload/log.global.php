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
            ],
            'processors' => [
                'requestid' => [
                    'name' => Laminas\Log\Processor\RequestId::class,
                ],
            ],
        ],
    ],
];
