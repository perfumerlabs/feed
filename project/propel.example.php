<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'feed' => [
                    'adapter' => 'pgsql',
                    'dsn' => 'pgsql:host=db;port=5432;dbname=feed',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'settings' => [
                        'charset' => 'utf8',
                        'queries' => [
                            'utf8' => "SET NAMES 'UTF8'"
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
