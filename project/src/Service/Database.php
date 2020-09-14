<?php

namespace Feed\Service;

use Envms\FluentPDO\Query;
use Perfumer\Helper\Text;

class Database
{
    private $db;

    private $host;

    private $port;

    private $username;

    private $password;

    private $pdo;

    public function __construct(
        $db,
        $host,
        $port,
        $username,
        $password
    )
    {
        $this->db = $db;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function getPdo()
    {
        if (!$this->pdo) {
            $this->pdo = new \PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->db}", $this->username, $this->password);
        }

        return $this->pdo;
    }

    public function getQuery()
    {
        return new Query($this->pdo);
    }

    public function insertDocument(Client $client, string $collection, $data): ?string
    {
        $pdo = $this->getPdo();

        if (!preg_match('/^[a-z0-9_]+$/', $collection)) {
            return null;
        }

        $key = $this->generateDocumentKey($collection);

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $query = "
            INSERT INTO \"$collection\" (\"client_id\", \"key\", \"data\", \"created_at\")
            VALUES (:client_id, :key, :data, :created_at)
        ";

        $created_at = date("Y-m-d H:i:s");
        $client_id = $client->getId();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('key', $key);
        $stmt->bindParam('data', $data);
        $stmt->bindParam('created_at', $created_at);
        $stmt->bindParam('client_id', $client_id);
        $stmt->execute();

        return $key;
    }

    public function getDocumentIdByKey(string $collection, ?string $key): ?int
    {
        if (!$key || !preg_match('/^[a-z0-9_]+$/', $collection)) {
            return null;
        }

        $pdo = $this->getPdo();

        /** @noinspection SqlNoDataSourceInspection */
        $query = "
            SELECT \"id\" FROM \"$collection\"
            WHERE \"key\" = :key
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('key', $key);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return count($result) > 0 ? $result[0]['id'] : null;
    }

    public function getDocuments(string $collection, int $from_id): array
    {
        if (!preg_match('/^[a-z0-9_]+$/', $collection)) {
            return [];
        }

        $pdo = $this->getPdo();

        /** @noinspection SqlNoDataSourceInspection */
        $query = "
                SELECT \"key\", \"data\" FROM \"$collection\"
                WHERE \"id\" > :from_id
                LIMIT {$this->fetch_limit}
            ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('from_id', $from_id);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $array = [];

        foreach ($result as $item) {
            $array[] = [
                'key' => $item['key'],
                'data' => json_decode($item['data'], true),
            ];
        }

        return $array;
    }

    public function countDocuments(string $collection, int $from_id): int
    {
        if (!preg_match('/^[a-z0-9_]+$/', $collection)) {
            return 0;
        }

        $pdo = $this->getPdo();

        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlDialectInspection */
        $query = "
                SELECT COUNT(\"id\") AS nb_documents FROM \"$collection\"
                WHERE \"id\" > :from_id
            ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('from_id', $from_id);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return count($result) > 0 ? $result[0]['nb_documents'] : 0;
    }

    public function createTable(string $name): bool
    {
        $pdo = $this->getPdo();

        if (!preg_match('/^[a-z0-9_]+$/', $name)) {
            return false;
        }

        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $query = 'CREATE TABLE IF NOT EXISTS "public"."' . $name . '"
            (
                "id" bigserial NOT NULL,
                "client_id" INTEGER NOT NULL,
                "key" VARCHAR(255) NOT NULL,
                "data" TEXT NOT NULL,
                "created_at" TIMESTAMP,
                PRIMARY KEY ("id"),
                CONSTRAINT "' . $name . '_key" UNIQUE ("key")
            );'
        ;

        $stmt = $pdo->prepare($query);

        return $stmt->execute();
    }

    private function generateDocumentKey($collection)
    {
        $pdo = $this->getPdo();

        do {
            $key = Text::generateString(50);

            /** @noinspection SqlNoDataSourceInspection */
            $query = "
                SELECT \"id\" FROM \"$collection\"
                WHERE \"key\" = :key
            ";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam('key', $key);
            $stmt->execute();

            $result = $stmt->fetchAll();
            $count = count($result);
        } while ($count > 0);

        return $key;
    }
}