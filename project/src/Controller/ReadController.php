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
        $badge_collection = $this->f('badge_collection');
        $badge_user = $this->f('badge_user');

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
            $recipients = $database->setIsRead($collection, $ids);

            if ($recipients) {
                foreach ($recipients as $recipient){
                    if ($this->hasCentrifugo()) {
                        $centrifugo->sendIsRead($ids, $recipient);
                    }

                    if ($this->hasBadges() && $badge_collection) {
                        $badges->deleteRecords($badge_collection, ($badge_user ?: $recipient), $ids);
                    }
                }
            }

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }
}