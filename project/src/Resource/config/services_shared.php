<?php

return [
    'gateway' => [
        'shared' => true,
        'class' => 'Project\\Gateway',
        'arguments' => ['#application', '#gateway.http', '#gateway.console']
    ],

    'database' => [
        'shared' => true,
        'class' => 'Feed\\Service\\Database',
        'arguments' => [
            '@database/db',
            '@database/host',
            '@database/port',
            '@database/username',
            '@database/password',
        ]
    ],

    'centrifugo' => [
        'shared' => true,
        'class' => 'Feed\\Service\\Centrifugo',
        'arguments' => [
            '@centrifugo/host',
            '@centrifugo/api_key',
            '@centrifugo/secret_key',
            '@centrifugo/module',
        ]
    ],

    'badges' => [
        'shared' => true,
        'class' => 'Feed\\Service\\Badges',
        'arguments' => [
            '@badges/host',
        ]
    ],
];