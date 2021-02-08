<?php

return [
    'propel' => [
        'bin'           => 'vendor/bin/propel',
        'project'       => 'feed',
        'database'      => 'pgsql',
        'dsn'           => 'pgsql:host=db;port=5432;dbname=feed',
        'db_user'       => 'postgres',
        'db_password'   => 'postgres',
        'platform'      => 'pgsql',
        'config_dir'    => './',
        'schema_dir'    => 'src/Resource/propel/schema',
        'model_dir'     => 'src/Model',
        'migration_dir' => 'src/Resource/propel/migration',
        'migration_table' => 'feed_propel_migration',
    ],
    'database' => [
        'db' => 'feed',
        'host' => 'db',
        'port' => '5432',
        'username' => 'postgres',
        'password' => 'postgres',
    ],
    'feed' => [
        'timezone' => 'Utc',
    ],
];