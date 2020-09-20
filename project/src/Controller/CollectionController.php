<?php


namespace Feed\Controller;


use Feed\Model\FeedCollection;
use Feed\Service\Database;

class CollectionController extends LayoutController
{
    public function post()
    {
        $name = $this->f('name');

        $this->validateNotEmpty($name, 'name');
        $this->validateRegex($name, 'name', '/^[a-z0-9_]+$/');

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $created = $database->createTable($name);

            if ($created) {
                $coll = new FeedCollection();
                $coll->setName($name);
                $coll->save();
            }

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }
}