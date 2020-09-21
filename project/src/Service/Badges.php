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

    public function addRecord($collection, $user, $id)
    {
        $data = [
            'collection' => $collection,
            'name' => $collection . '/' . 'record/' . $id,
            'user' => $user,
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

    public function deleteRecords($collection, $user, array $ids)
    {
        foreach ($ids as $id) {

            $data = [
                'collection' => $collection,
                'name' => $collection . '/' . 'record/' . $id,
                'user' => $user
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
