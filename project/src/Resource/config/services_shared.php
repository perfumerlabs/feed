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

    'facade.collection' => [
        'shared' => true,
        'class' => 'Feed\\Facade\\CollectionFacade',
        'arguments' => [
            '#database',
            '#domain.collection',
        ],
    ],

    'domain.collection' => [
        'shared' => true,
        'class' => 'Feed\\Domain\\CollectionDomain',
    ],

    'propel.connection_manager' => [
        'class' => 'Propel\\Runtime\\Connection\\ConnectionManagerSingle',
        'after' => function(\Perfumer\Component\Container\Container $container, \Propel\Runtime\Connection\ConnectionManagerSingle $connection_manager) {
            $configuration = [
                'dsn' => $container->getParam('propel/dsn'),
                'user' => $container->getParam('propel/db_user'),
                'password' => $container->getParam('propel/db_password'),
                'settings' => [
                    'charset' => 'utf8',
                ]
            ];

            $schema = $container->getParam('propel/db_schema');

            if ($schema !== 'public' && $schema !== null) {
                $configuration['settings']['queries'] = [
                    'schema' => "SET search_path TO " . $schema
                ];
            }

            $connection_manager->setConfiguration($configuration);
        }
    ],
];