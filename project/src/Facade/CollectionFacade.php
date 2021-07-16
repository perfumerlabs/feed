<?php

namespace Feed\Facade;

use Feed\Domain\CollectionDomain;
use Feed\Service\Database;

class CollectionFacade
{
    protected $database;

    protected $collectionDomain;

    public function __construct(Database $database, CollectionDomain $collectionDomain)
    {
        $this->database = $database;
        $this->collectionDomain = $collectionDomain;
    }

    public function create($name, array $data = []): void
    {
        $created = $this->database->createTable($name);

        if ($created) {
            $this->collectionDomain->save($name, $data);
        }
    }
}