<?php

declare(strict_types=1);

return [
    'fph' => [
        'arcgis'       => [
            'username'         => getenv('ARCGIS_USERNAME'),
            'password'         => getenv('ARCGIS_PASSWORD'),
            'token_url'        => getenv('ARCGIS_URL_GEN_TOKEN'),
            'find_address_url' => getenv('ARCGIS_URL_FIND_ADDRESS'),
        ]
    ],
];
