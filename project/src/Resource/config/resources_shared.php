<?php

return [
    'propel' => [
        'bin'           => 'vendor/bin/propel',
        'project'       => 'feed',
        'database'      => 'pgsql',
        'dsn'           => 'pgsql:host=PG_HOST;port=PG_PORT;dbname=PG_DATABASE',
        'db_user'       => 'PG_USER',
        'db_password'   => 'PG_PASSWORD',
        'platform'      => 'pgsql',
        'config_dir'    => 'src/Resource/propel/connection',
        'schema_dir'    => 'src/Resource/propel/schema',
        'model_dir'     => 'src/Model',
        'migration_dir' => 'src/Resource/propel/migration',
    ],
    'database' => [
        'db' => 'PG_DATABASE',
        'host' => 'PG_HOST',
        'port' => 'PG_PORT',
        'username' => 'PG_USER',
        'password' => 'PG_PASSWORD',
    ],
];