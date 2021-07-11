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

    'domain.collection' => [
        'shared' => true,
        'class' => 'Feed\\Domain\\CollectionDomain',
    ],

    'propel.connection_manager' => [
        'init' => function(\Perfumer\Component\Container\Container $container) {
            $dsn_slaves = $container->getParam('db/slaves');

            if ($dsn_slaves) {
                return $container->get('propel.connection_manager_master_slave');
            } else {
                return $container->get('propel.connection_manager_single');
            }
        }
    ],

    'propel.connection_manager_single' => [
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

    'propel.connection_manager_master_slave' => [
        'class' => 'Propel\\Runtime\\Connection\\ConnectionManagerMasterSlave',
        'after' => function(\Perfumer\Component\Container\Container $container, \Propel\Runtime\Connection\ConnectionManagerMasterSlave $connection_manager) {
            $dsn_master = $container->getParam('propel/dsn');
            $dsn_slaves = $container->getParam('db/slaves');
            $user = $container->getParam('propel/db_user');
            $password = $container->getParam('propel/db_password');
            $schema = $container->getParam('propel/db_schema');

            $default_connection = [
                'user' => $user,
                'password' => $password,
                'settings' => [
                    'charset' => 'utf8',
                ]
            ];

            if ($schema !== 'public' && $schema !== null) {
                $default_connection['settings']['queries'] = [
                    'schema' => "SET search_path TO " . $schema
                ];
            }

            $write_configuration = $default_connection;
            $write_configuration['dsn'] = $dsn_master;

            $connection_manager->setWriteConfiguration($write_configuration);

            if ($dsn_slaves) {
                $connections = [];

                if (is_string($dsn_slaves)) {
                    $dsn_slaves = explode(',', $dsn_slaves);
                }

                foreach ($dsn_slaves as $dsn_slave) {
                    $read_configuration = $default_connection;
                    $read_configuration['dsn'] = $dsn_slave;

                    $connections[] = $read_configuration;
                }

                if ($connections) {
                    $connection_manager->setReadConfiguration($connections);
                }
            }
        }
    ],
];