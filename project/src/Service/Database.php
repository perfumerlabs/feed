<?php

namespace Feed\Service;

use Envms\FluentPDO\Query;
use Feed\Model\FeedCollectionQuery;
use Feed\Model\Map\FeedCollectionTableMap;
use Perfumer\Helper\Text;
use Propel\Runtime\Propel;

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
            $this->pdo = Propel::getWriteConnection(FeedCollectionTableMap::DATABASE_NAME);
        }

        return $this->pdo;
    }

    public function getQuery()
    {
        return new Query($this->pdo);
    }

    public function getRecords($collection, $recipient, $sender, $thread, $id, $limit): array
    {
        $pdo = $this->getPdo();

        $limit = $limit && $limit > 0 ?: 25;

        $where = '';

        if($recipient){
            $where .= "AND recipient = :recipient ";
        }
        if($sender){
            $where .= "AND sender = :sender ";
        }
        if($id){
            $where .= "AND id > :id ";
        }
        if($thread){
            $where .= "AND thread = :thread ";
        }

        $where = substr($where, 3, strlen($where));

        $query = "
                SELECT * FROM $collection
                WHERE $where
                ORDER BY id DESC
                LIMIT $limit
            ";

        $stmt = $pdo->prepare($query);

        if($recipient){
            $stmt->bindParam('recipient', $recipient);
        }
        if($sender){
            $stmt->bindParam('sender', $sender);
        }
        if($id){
            $stmt->bindParam('id', $id);
        }
        if($thread){
            $stmt->bindParam('thread', $thread);
        }

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($result as $key => $item) {
            if(array_key_exists('payload', $item)){
                $result[$key]['payload'] = json_decode($item['payload'], true);
            }
        }

        return $result;
    }

    public function setIsRead($collection, array $ids)
    {
        $pdo = $this->getPdo();

        $ids = implode(',', $ids);

        $query = sprintf("
            UPDATE \"$collection\" SET is_read = true WHERE id IN (%s)
            RETURNING \"recipient\"
        ", $ids);

        $stmt = $pdo->prepare($query);

        if(!$stmt->execute()){
            return false;
        }

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_unique(array_column($result, 'recipient'));
    }

    public function createTable(string $name): bool
    {
        $pdo = $this->getPdo();

        if (!preg_match('/^[a-z0-9_]+$/', $name)) {
            return false;
        }

        /** @noinspection SqlDialectInspection */

        $query = sprintf('CREATE TABLE IF NOT EXISTS "public"."%s"
                (
                    "id" bigserial NOT NULL,
                    "recipient" VARCHAR(255) NOT NULL,
                    "sender" VARCHAR(255),
                    "thread" VARCHAR(255),
                    "title" VARCHAR(255),
                    "text" TEXT,
                    "image" VARCHAR(255),
                    "payload" JSON,
                    "created_at" TIMESTAMP,
                    "is_read" BOOLEAN DEFAULT \'f\' NOT NULL,
                    PRIMARY KEY ("id")
                );', $name);

        $stmt = $pdo->prepare($query);
        if(!$stmt->execute()){
            return false;
        }

        /** @noinspection SqlDialectInspection */
        $pdo->query(sprintf('CREATE INDEX "%s_recipient_i" ON "%s" ("recipient");', $name, $name));

        /** @noinspection SqlDialectInspection */
        $pdo->query(sprintf('CREATE INDEX "%s_sender_i" ON "%s" ("sender");', $name, $name));

        /** @noinspection SqlDialectInspection */
        $pdo->query(sprintf('CREATE INDEX "%s_thread_i" ON "%s" ("thread");', $name, $name));

        /** @noinspection SqlDialectInspection */
        $pdo->query(sprintf('CREATE INDEX "%s_created_at_i" ON "%s" ("created_at");', $name, $name));

        return true;
    }

    public function hasCollection(string $collection)
    {
        return FeedCollectionQuery::create()
            ->findOneByName($collection);
    }

    public function insert($collection, $recipient, array $data)
    {
        $pdo = $this->getPdo();

        /** @noinspection SqlDialectInspection */
        $query = "
            INSERT INTO \"$collection\" (\"recipient\", \"sender\", \"thread\", \"title\", \"text\", \"image\", \"created_at\")
            VALUES (:recipient, :sender, :thread, :title, :text, :image, :created_at) 
            RETURNING \"id\"
        ";

        $created_at = date("Y-m-d H:i:s");
        $sender = $data['sender'] ?? null;
        $thread = $data['thread'] ?? null;
        $title = $data['title'] ?? null;
        $text = $data['text'] ?? null;
        $image = $data['image'] ?? null;

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('recipient', $recipient);
        $stmt->bindParam('sender', $sender);
        $stmt->bindParam('thread', $thread);
        $stmt->bindParam('title', $title);
        $stmt->bindParam('text', $text);
        $stmt->bindParam('image', $image);
        $stmt->bindParam('created_at', $created_at);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}