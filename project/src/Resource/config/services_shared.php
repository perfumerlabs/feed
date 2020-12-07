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
            '@feed/timezone',
        ]
    ],

    'centrifugo' => [
        'shared' => true,
        'class' => 'Feed\\Service\\Centrifugo',
        'arguments' => [
            '@centrifugo/host',
            '@centrifugo/api_key',
            '@centrifugo/secret_key',
        ]
    ],

    'badges' => [
        'shared' => true,
        'class' => 'Feed\\Service\\Badges',
        'arguments' => [
            '@badges/host',
        ]
    ],

    'repository.record' => [
        'shared' => true,
        'class' => 'Feed\\Repository\\RecordRepository',
        'arguments' => [
            '@feed/timezone',
        ]
    ],

    'domain.collection' => [
        'shared' => true,
        'class' => 'Feed\\Domain\\CollectionDomain',
    ],
];