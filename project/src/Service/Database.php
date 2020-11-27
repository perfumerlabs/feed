<?php

namespace Feed\Service;

use Envms\FluentPDO\Query;
use Feed\Model\FeedCollectionQuery;
use Feed\Model\Map\FeedCollectionTableMap;
use Perfumer\Helper\Arr;
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

    public function getCollectionName($collection)
    {
        return 'feed_data_' . preg_replace('/[^a-zA-Z0-9_]/', '', $collection);
    }

    public function getRecords(array $data): array
    {
        $collection = $data['collection'] ?? null;
        $recipient = $data['recipient'] ?? null;
        $sender = $data['sender'] ?? null;
        $thread = $data['thread'] ?? null;
        $id = $data['id'] ?? null;
        $limit = $data['limit'] ?? null;
        $search = $data['search'] ?? null;
        $order = $data['order'] ?? null;
        $is_read = $data['is_read'] ?? null;
        $user = $data['user'] ?? null;

        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        $limit = ($limit && $limit > 0) ? (int) $limit : 25;

        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $where = '';

        if($user){
            $where .= "AND (recipient = :recipient OR sender = :sender) ";
        }else {
            if ($recipient) {
                $where .= "AND recipient = :recipient ";
            }

            if ($sender) {
                $where .= "AND sender = :sender ";
            }
        }

        if ($id) {
            if ($order === 'desc') {
                $where .= "AND id < :id ";
            } else {
                $where .= "AND id > :id ";
            }
        }

        if ($thread) {
            $thread = "'" . implode("','", is_array($thread) ? $thread : [$thread]) . "'";

            $where .= "AND thread IN ($thread) ";
        }

        if ($search) {
            $where .= "AND (title ILIKE :title OR text ILIKE :text) ";
        }

        if ($is_read !== null) {
            if ($is_read) {
                $where .= "AND is_read = true ";
            } else {
                $where .= "AND is_read = false ";
            }
        }

        if ($where) {
            $where = 'WHERE ' . substr($where, 3, strlen($where));
        }

        $query = "
                SELECT * FROM $collection
                $where
                ORDER BY created_at $order
                LIMIT $limit
            ";

        $stmt = $pdo->prepare($query);

        if($user){
            $stmt->bindParam('sender', $user, \PDO::PARAM_STR);
            $stmt->bindParam('recipient', $user, \PDO::PARAM_STR);
        }

        if ($recipient){
            $stmt->bindParam('recipient', $recipient, \PDO::PARAM_STR);
        }

        if ($sender){
            $stmt->bindParam('sender', $sender, \PDO::PARAM_STR);
        }

        if ($id){
            $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        }

        if ($search){
            $param = "%$search%";
            $stmt->bindParam('title', $param);
            $stmt->bindParam('text', $param);
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

    public function readRecord($collection, $id)
    {
        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        $query = "
            UPDATE \"$collection\" SET is_read = true WHERE id = :id
            RETURNING \"recipient\"
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result;
    }

    public function unreadRecord($collection, $id)
    {
        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        $query = "
            UPDATE \"$collection\" SET is_read = false WHERE id = :id
            RETURNING \"recipient\"
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result;
    }

    public function readRecords($collection, $recipient)
    {
        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        $query = "
            UPDATE \"$collection\" SET is_read = true WHERE recipient = :recipient
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('recipient', $recipient);
        $stmt->execute();
    }

    public function createTable(string $name): bool
    {
        $pdo = $this->getPdo();

        if (!preg_match('/^[a-z0-9_]+$/', $name)) {
            return false;
        }

        $name = $this->getCollectionName($name);

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
                    "payload" JSONB,
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

        $collection = $this->getCollectionName($collection);

        /** @noinspection SqlDialectInspection */
        $query = "
            INSERT INTO \"$collection\" (\"recipient\", \"sender\", \"thread\", \"title\", \"text\", \"image\", \"created_at\", \"payload\")
            VALUES (:recipient, :sender, :thread, :title, :text, :image, :created_at, :payload) 
            RETURNING \"id\", \"created_at\"
        ";

        $created_at = $data['created_at'] ?? null;
        $sender = $data['sender'] ?? null;
        $thread = $data['thread'] ?? null;
        $title = $data['title'] ?? null;
        $text = $data['text'] ?? null;
        $image = $data['image'] ?? null;
        $payload = array_key_exists('payload', $data) ? json_encode($data['payload']) : null;

        if (!$created_at) {
            $created_at = date("Y-m-d H:i:s");
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('recipient', $recipient);
        $stmt->bindParam('sender', $sender);
        $stmt->bindParam('thread', $thread);
        $stmt->bindParam('title', $title);
        $stmt->bindParam('text', $text);
        $stmt->bindParam('image', $image);
        $stmt->bindParam('created_at', $created_at);
        $stmt->bindParam('payload', $payload);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getRecord($collection, $id)
    {
        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        $query = "
                SELECT * FROM $collection
                WHERE id = :id
            ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function delete($collection, $id)
    {
        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        /** @noinspection SqlDialectInspection */
        $query = "
            DELETE FROM \"$collection\" 
            WHERE \"id\" = :id
            RETURNING \"recipient\"
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deleteAll($collection, $recipient): bool
    {
        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        /** @noinspection SqlDialectInspection */
        $query = "
            DELETE FROM \"$collection\" 
            WHERE \"recipient\" = :recipient
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam('recipient', $recipient);
        $stmt->execute();

        return true;
    }

    public function update(string $collection, array $where, array $set)
    {
        $where = Arr::fetch($where, [
            'recipient',
            'sender',
            'thread',
            'user'
        ]);

        $set = Arr::fetch($set, [
            'recipient',
            'sender',
            'thread',
            'user',
            'title',
            'text',
            'image',
            'payload',
        ]);

        if(!$where || !$set){
            return false;
        }

        $pdo = $this->getPdo();

        $collection = $this->getCollectionName($collection);

        $set_query = null;

        foreach ($set as $key => $item){
            if($key === 'user'){
                continue;
            }
            if(!$set_query){
                $set_query = sprintf('SET "%s" = :new_%s ', $key, $key);
            }else {
                $set_query .= sprintf(', "%s" = :new_%s', $key, $key);
            }
        }

        $where_query = null;

        foreach ($where as $key => $item){
            if($key === 'user'){
                continue;
            }
            if(!$where_query){
                $where_query .=  sprintf('WHERE "%s" = :%s', $key, $key);
            }else {
                $where_query .= sprintf('AND "%s" = :%s', $key, $key);
            }
        }

        $user_where = $where['user'] ?? null;
        $user_set = $set['user'] ?? null;

        if($user_where && $user_set){
            /** @noinspection SqlDialectInspection */
            $set_query .= ($set_query ? ', ' : ' SET ') . 'recipient = CASE
                            WHEN recipient = :user_where THEN :user_set
                            ELSE recipient
                            END, 
                            sender = CASE
                            WHEN sender = :user_where THEN :user_set
                            ELSE sender
                            END';
        }

        if($user_where){
            $where_query = ($where_query ? 'AND' : 'WHERE') . ' (recipient = :user_where OR sender = :user_where) ';
        }

        if(!$where_query || !$set_query){
            return false;
        }

        /** @noinspection SqlDialectInspection */
        $query = sprintf("UPDATE \"$collection\" %s %s;", $set_query, $where_query);

        $stmt = $pdo->prepare($query);

        if($user_where && $user_set){
            $stmt->bindParam('user_set', $user_set, \PDO::PARAM_STR);
        }

        if($user_where){
            $stmt->bindParam('user_where', $user_where, \PDO::PARAM_STR);
        }

        if(isset($where['recipient'])){
            $stmt->bindParam('recipient', $where['recipient'], \PDO::PARAM_STR);
        }

        if(isset($where['sender'])){
            $stmt->bindParam('sender', $where['sender'], \PDO::PARAM_STR);
        }

        if(isset($where['thread'])){
            $stmt->bindParam('thread', $where['thread'], \PDO::PARAM_STR);
        }

        if(isset($set['recipient'])){
            $stmt->bindParam('new_recipient', $set['recipient'], \PDO::PARAM_STR);
        }

        if(isset($set['sender'])){
            $stmt->bindParam('new_sender', $set['sender'], \PDO::PARAM_STR);
        }

        if(isset($set['thread'])){
            $stmt->bindParam('new_thread', $set['thread'], \PDO::PARAM_STR);
        }

        if(isset($set['title'])){
            $stmt->bindParam('new_title', $set['title'], \PDO::PARAM_STR);
        }

        if(isset($set['text'])){
            $stmt->bindParam('new_text', $set['text'], \PDO::PARAM_STR);
        }

        if(isset($set['image'])){
            $stmt->bindParam('new_image', $set['image'], \PDO::PARAM_STR);
        }

        if(isset($set['payload'])){
            $payload = json_encode($set['payload'], true);
            $stmt->bindParam('new_payload', $payload);
        }

        return $stmt->execute();
    }
}
