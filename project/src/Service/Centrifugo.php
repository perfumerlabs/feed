<?php

namespace Feed\Service;

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

    /**
     * @var string
     */
    private $module;

    public function __construct($host, $api_key, $secret_key, $module)
    {
        $this->host = $host;
        $this->api_key = $api_key;
        $this->secret_key = $secret_key;
        $this->module = $module;
    }

    public function formatRecord($data)
    {
        return [
            [
                "module" => $this->module,
                "event" => "record",
                "content" => [
                    "record" => [
                        "id" => $data['id'],
                        'recipient' => $data['recipient'],
                        'sender' => $data['sender'] ?? null,
                        'thread' => $data['thread'] ?? null,
                        'title' => $data['title'] ?? null,
                        'text' => $data['text'] ?? null,
                        'image' => $data['image'] ?? null,
                        'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                    ]
                ]
            ]
        ];
    }

    public function formatIsRead($id, $recipient)
    {
        return [
            [
                "module" => $this->module,
                "event" => "read",
                "content" => [
                    "record" => [
                        "id" => $id,
                        'recipient' => $recipient,
                    ]
                ]
            ]
        ];
    }

    public function sendRecord($recipient, $data)
    {
        if (!$this->getCentrifugoClient()) {
            return;
        }

        $this->getCentrifugoClient()->publish($recipient . 'r', $this->formatRecord($data));
    }

    public function sendIsRead(array $ids, $recipient)
    {
        if (!$this->getCentrifugoClient()) {
            return;
        }

        foreach ($ids as $id) {
            $this->getCentrifugoClient()->publish($recipient . 'r', $this->formatIsRead($id, $recipient));
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
