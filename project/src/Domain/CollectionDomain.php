<?php

namespace Feed\Domain;

use Feed\Model\FeedCollection;
use Feed\Model\FeedCollectionQuery;

class CollectionDomain
{
    public function save($name, array $data): ?FeedCollection
    {
        $websocket_module = $data['websocket_module'] ?? null;
        $badges_collection = $data['badges_collection'] ?? null;
        $badges_prefix = $data['badges_prefix'] ?? null;

        $coll = FeedCollectionQuery::create()
            ->filterByName($name)
            ->findOneOrCreate();

        $coll->setWebsocketModule($websocket_module);
        $coll->setBadgesCollection($badges_collection);
        $coll->setBadgesPrefix($badges_prefix);
        $coll->save();

        return $coll;
    }
}