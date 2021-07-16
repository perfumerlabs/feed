<?php

namespace Feed\Command;

use Feed\Facade\CollectionFacade;
use Feed\Service\Database;
use Perfumer\Framework\Controller\PlainController;
use Perfumer\Framework\Router\ConsoleRouterControllerHelpers;

class StartupCommand extends PlainController
{
    use ConsoleRouterControllerHelpers;

    public function action()
    {
        $real_host = $this->getContainer()->getParam('pg/real_host');
        $host = $this->getContainer()->getParam('pg/host');
        $port = $this->getContainer()->getParam('pg/port');
        $user = $this->getContainer()->getParam('pg/user');
        $password = $this->getContainer()->getParam('pg/password');
        $database = $this->getContainer()->getParam('pg/database');
        $schema = $this->getContainer()->getParam('pg/schema');

        if (!$real_host) {
            echo 'Environment variable PG_REAL_HOST is not defined, so skipping database and/or schema creation.' . PHP_EOL;
        } else {
            while (true) {
                try {
                    $dbh = new \PDO("pgsql:host=$real_host;port=$port", $user, $password);
                } catch (\PDOException $e) {
                    echo 'Could not connect to PostgreSQL server to create database. Delaying...' . PHP_EOL;
                    sleep(3);
                    continue;
                }

                $dbh->exec("CREATE DATABASE \"$database\";");

                echo 'Database created' . PHP_EOL;

                if ($schema !== 'public') {
                    try {
                        $dbh = new \PDO("pgsql:host=$real_host;port=$port;dbname=$database", $user, $password);
                    } catch (\PDOException $e) {
                        echo 'Could not connect to PostgreSQL database to create schema. Delaying...' . PHP_EOL;
                        sleep(3);
                        continue;
                    }

                    $dbh->exec("CREATE SCHEMA \"$schema\";");

                    echo 'Schema created' . PHP_EOL;
                }

                break;
            }
        }

        echo shell_exec('cd /opt/feed && /usr/bin/php cli framework propel/migrate');

        $predefined_collections = $this->getContainer()->getParam('feed/collections');

        if ($predefined_collections) {
            $predefined_collections = explode(',', $predefined_collections);
            $predefined_collections = array_map(function ($v) {
                return trim($v);
            }, $predefined_collections);

            /** @var Database $database */
            $database = $this->s('database');

            /** @var CollectionFacade $facade */
            $facade = $this->s('facade.collection');

            $con = $database->getPdo();

            foreach ($predefined_collections as $predefined_collection) {
                $con->beginTransaction();

                try {
                    $facade->create($predefined_collection);

                    $con->commit();
                } catch (\Throwable $e) {
                    $con->rollBack();
                }
            }
        }
    }
}
