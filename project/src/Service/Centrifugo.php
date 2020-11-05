<?php

namespace Feed\Service;

use Feed\Model\FeedCollectionQuery;
use phpcent\Client;

class Centrifugo
{
    /**
     * @var Client
     */
    private $centrifugo_client;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $api_key;

    /**
     * @var string
     */
    private $secret_key;

    public function __construct($host, $api_key, $secret_key)
    {
        $this->host = $host;
        $this->api_key = $api_key;
        $this->secret_key = $secret_key;
    }

    public function sendRecord($collection_name, $recipient, $data)
    {
        $collection = FeedCollectionQuery::create()->findOneByName($collection_name);

        if (
            !$recipient ||
            !$collection ||
            !$collection->getWebsocketModule() ||
            !$this->getCentrifugoClient()
        ) {
            return;
        }

        $this->getCentrifugoClient()->publish($recipient, [
            'module' => $collection->getWebsocketModule(),
            'event' => $collection_name . '.record',
            'content' => [
                'record' => $data
            ]
        ]);
    }

    public function sendIsRead($collection_name, array $ids, $recipient)
    {
        $collection = FeedCollectionQuery::create()->findOneByName($collection_name);

        if (
            !$recipient ||
            !$collection ||
            !$collection->getWebsocketModule() ||
            !$this->getCentrifugoClient()
        ) {
            return;
        }

        foreach ($ids as $id) {
            $this->getCentrifugoClient()->publish($recipient, [
                'module' => $collection->getWebsocketModule(),
                'event' => $collection_name . '.record.read',
                'content' => [
                    'record' => [
                        'id' => $id,
                        'recipient' => $recipient,
                    ]
                ]
            ]);
        }
    }

    private function getCentrifugoClient()
    {
        if (!$this->centrifugo_client && $this->host) {
            $this->centrifugo_client = new Client($this->host, $this->api_key, $this->secret_key);
            $this->centrifugo_client->setConnectTimeoutOption(5);
            $this->centrifugo_client->setTimeoutOption(5);
        }

        return $this->centrifugo_client;
    }
}
