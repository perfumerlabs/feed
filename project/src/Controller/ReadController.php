<?php


namespace Feed\Controller;

use Feed\Service\Centrifugo;
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

        /** @var Centrifugo $centrifugo */
        $centrifugo = $this->s('centrifugo');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $recipient = $database->setIsRead($collection, $id);

            if($recipient && $this->getContainer()->getParam('centrifugo/host')){
                $centrifugo->sendIsRead($id, $recipient);
            }

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();
        }
    }
}