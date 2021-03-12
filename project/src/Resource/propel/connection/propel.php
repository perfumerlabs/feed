<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'feed' => [
                    'adapter' => 'pgsql',
                    'dsn' => 'pgsql:host=PG_HOST;port=PG_PORT;dbname=PG_DATABASE',
                    'user' => 'PG_USER',
                    'password' => 'PG_PASSWORD',
                    'settings' => [
                        'charset' => 'utf8',
                        'queries' => [
                            'utf8' => "SET NAMES 'UTF8'",
                            'schema' => "SET search_path TO PG_SCHEMA"
                        ]
                    ],
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'feed',
            'connections' => ['feed']
        ],
        'generator' => [
            'defaultConnection' => 'feed',
            'connections' => ['feed']
        ]
    ]
];