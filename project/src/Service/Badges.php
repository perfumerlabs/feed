<?php

namespace Feed\Service;

use Feed\Model\FeedCollection;
use Feed\Model\FeedCollectionQuery;
use GuzzleHttp\Client as Guzzle;

class Badges
{
    private $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function addRecord($collection_name, $user, $id)
    {
        $collection = FeedCollectionQuery::create()->findOneByName($collection_name);

        if (
            !$user ||
            !$collection ||
            !$collection->getBadgesCollection() ||
            !$this->host
        ) {
            return;
        }

        $data = [
            'collection' => $collection->getBadgesCollection(),
            'name' => $this->getBadgeName($collection, $id),
            'user' => $user,
        ];

        try {
            $client = new Guzzle();

            $client->post($this->host . '/badge', [
                'connect_timeout' => 5,
                'read_timeout'    => 5,
                'timeout'         => 5,
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);
        } catch (\Exception $e) {
        }
    }

    public function deleteRecord($collection_name, $user, $id)
    {
        $collection = FeedCollectionQuery::create()->findOneByName($collection_name);

        if (
            !$user ||
            !$collection ||
            !$collection->getBadgesCollection() ||
            !$this->host
        ) {
            return;
        }

        $data = [
            'collection' => $collection->getBadgesCollection(),
            'name' => $this->getBadgeName($collection, $id),
            'user' => $user,
        ];

        try {
            $client = new Guzzle();

            $client->delete($this->host . '/badge', [
                'connect_timeout' => 5,
                'read_timeout'    => 5,
                'timeout'         => 5,
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);
        } catch (\Exception $e) {
        }
    }

    public function deleteAll($collection_name, $user)
    {
        $collection = FeedCollectionQuery::create()->findOneByName($collection_name);

        if (
            !$user ||
            !$collection ||
            !$collection->getBadgesCollection() ||
            !$this->host
        ) {
            return;
        }

        $data = [
            'collection' => $collection->getBadgesCollection(),
            'name' => $this->getBadgeName($collection),
            'user' => $user,
        ];

        try {
            $client = new Guzzle();

            $client->delete($this->host . '/badges', [
                'connect_timeout' => 5,
                'read_timeout'    => 5,
                'timeout'         => 5,
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ]);
        } catch (\Exception $e) {
        }
    }

    private function getBadgeName(FeedCollection $collection, $id = null)
    {
        $name = $collection->getName();

        if ($collection->getBadgesPrefix()) {
            $name = $collection->getBadgesPrefix() . '/' . $name;
        }

        $name .= '/' . 'record';

        if ($id) {
            $name .= '/' . $id;
        }

        return $name;
    }
}
