<?php


namespace Feed\Controller\Record;

use Feed\Controller\LayoutController;
use Feed\Service\Database;

class ReadController extends LayoutController
{
    public function post()
    {
        $collection = $this->f('collection');
        $id = $this->f('id');

        $this->validateCollection($collection);
        $this->validateNotEmpty($id, 'id');

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $database->setIsRead($collection, $id);

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();
        }
    }
}