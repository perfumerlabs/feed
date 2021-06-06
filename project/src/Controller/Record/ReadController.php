<?php


namespace Feed\Controller\Record;

use Feed\Controller\LayoutController;
use Feed\Service\Badges;
use Feed\Service\Database;

class ReadController extends LayoutController
{
    public function post()
    {
        $collection = $this->f('collection');
        $id = (int) $this->f('id');
        $recipient = $this->f('recipient');
        $sender = $this->f('sender');
        $badge_user = $this->f('badge_user');

        $this->validateCollection($collection);
        $this->validateNotEmptyOneOfArray([
            'id' => $id,
            'recipient' => $recipient,
            'sender' => $sender,
        ]);

        /** @var Database $database */
        $database = $this->s('database');

        if(!$id){
            if($recipient && $sender) {
                if($record = $database->getRecordByRecipientSender($collection, $recipient, $sender)){
                    $id = $record['id'];
                }
            }

            if(!$id){
                $this->setStatusAndExit(false);
            }
        }

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $recipient = $database->readRecord($collection, $id);

            if (!$badge_user) {
                $badge_user = $recipient;
            }

            if ($this->hasBadges()) {
                $this->getProxy()->deferCallable(function () use ($collection, $badge_user, $id) {
                    /** @var Badges $badges */
                    $badges = $this->s('badges');
                    $badges->deleteRecord($collection, $badge_user, $id);
                });
            }

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }
}