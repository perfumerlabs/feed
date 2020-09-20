<?php

namespace Feed\Service;

use GuzzleHttp\Client as Guzzle;

class Badges
{
    private $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function addRecord($collection, $recipient, $id)
    {
        $data = [
            'collection' => $collection,
            'name' => 'records/' . $recipient,
            'user' => $recipient,
            'payload' => [
                'id' => $id
            ]
        ];

        try {
            $client = new Guzzle();

            $response = $client->post($this->host . '/badge', [
                'connect_timeout' => 5,
                'json' => [
                    $data
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);

            if($response->getStatusCode()) {
                return $response->getStatusCode();
            }else{
                return 500;
            }
        } catch (\Exception $e) {
        }
    }

    public function deleteRecords($collection, $recipient, array $ids)
    {
        foreach ($ids as $id) {

            $data = [
                'collection' => $collection,
                'name' => 'records/' . $recipient . '/' . $id,
                'user' => $recipient
            ];

            try {
                $client = new Guzzle();

                $response = $client->delete($this->host . '/badges', [
                    'connect_timeout' => 5,
                    'json' => [
                        $data
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'verify' => false
                ]);

                if ($response->getStatusCode()) {
                    return $response->getStatusCode();
                } else {
                    return 500;
                }
            } catch (\Exception $e) {
            }
        }
    }

}
