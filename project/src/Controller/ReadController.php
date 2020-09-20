<?php


namespace Feed\Controller;

use Feed\Service\Badges;
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

        $ids = is_array($id) ? $id : [$id];

        /** @var Database $database */
        $database = $this->s('database');

        /** @var Centrifugo $centrifugo */
        $centrifugo = $this->s('centrifugo');

        /** @var Badges $badges */
        $badges = $this->s('badges');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $recipient = $database->setIsRead($collection, $ids);

            if($recipient) {
                if ($this->hasCentrifugo()) {
                    $centrifugo->sendIsRead($ids, $recipient, $collection);
                }

                if ($this->hasBadges()) {
                    $badges->deleteRecords($collection, $recipient, $ids);
                }
            }

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();
        }
    }
}