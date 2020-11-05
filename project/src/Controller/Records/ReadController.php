<?php

namespace Feed\Controller\Records;

use Feed\Controller\LayoutController;
use Feed\Service\Badges;
use Feed\Service\Database;

class ReadController extends LayoutController
{
    public function post()
    {
        $collection = $this->f('collection');
        $recipient = (string) $this->f('recipient');
        $badge_user = $this->f('badge_user');

        $this->validateCollection($collection);
        $this->validateNotEmpty($recipient, 'recipient');

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $database->readRecords($collection, $recipient);

            if (!$badge_user) {
                $badge_user = $recipient;
            }

            if ($this->hasBadges()) {
                $this->getProxy()->deferCallable(function () use ($collection, $badge_user) {
                    /** @var Badges $badges */
                    $badges = $this->s('badges');
                    $badges->deleteAll($collection, $badge_user);
                });
            }

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }
}