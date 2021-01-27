<?php


namespace Feed\Controller;

use Feed\Repository\RecordRepository;
use Feed\Service\Badges;
use Feed\Service\Centrifugo;
use Feed\Service\Database;
use Perfumer\Helper\Arr;

class RecordController extends LayoutController
{
    public function post()
    {
        $collection = $this->f('collection');
        $recipient = $this->f('recipient');
        $websocket_channel = $this->f('websocket_channel');
        $badge_user = $this->f('badge_user');

        $this->validateCollection($collection);
        $this->validateNotEmpty($recipient, 'recipient');

        if (!$websocket_channel) {
            $websocket_channel = $recipient;
        }

        if (!$badge_user) {
            $badge_user = $recipient;
        }

        $data = Arr::fetch($this->f(), [
            'sender',
            'thread',
            'title',
            'text',
            'image',
            'created_at',
            'payload'
        ]);

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $inserted_data = $database->insert($collection, $recipient, $data);

            if($inserted_data) {
                $data['id'] = $inserted_data['id'];
                $data['created_at'] = $inserted_data['created_at'];
                $data['recipient'] = $recipient;

                if ($this->hasCentrifugo()) {
                    $this->getProxy()->deferCallable(function () use ($collection, $websocket_channel, $data) {
                        /** @var Centrifugo $centrifugo */
                        $centrifugo = $this->s('centrifugo');
                        $centrifugo->sendRecord($collection, $websocket_channel, $data);
                    });
                }

                if($this->hasBadges()){
                    $this->getProxy()->deferCallable(function () use ($collection, $badge_user, $data) {
                        /** @var Badges $badges */
                        $badges = $this->s('badges');
                        $badges->addRecord($collection, $badge_user, $data['id']);
                    });
                }

                /** @var RecordRepository $repository */
                $repository = $this->s('repository.record');

                $this->setContent([
                    'record' => $repository->format($data)
                ]);
            }

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();

            $this->forward('error', 'internalServerError', [$e]);
        }
    }
    
    public function get()
    {
        $collection = $this->f('collection');
        $id = $this->f('id');

        $this->validateCollection($collection);

        if (!$id) {
            $this->forward('error', 'pageNotFound', ["Record was not found"]);
        }

        /** @var Database $database */
        $database = $this->s('database');

        $record = $database->getRecord($collection, $id);

        if (!$record) {
            $this->forward('error', 'pageNotFound', ["Record was not found"]);
        }

        /** @var RecordRepository $repository */
        $repository = $this->s('repository.record');

        $this->setContent([
            'record' => $repository->format($record)
        ]);
    }

    public function delete()
    {
        $collection = $this->f('collection');
        $badge_user = $this->f('badge_user');

        $data = [
            'recipient' => $this->f('recipient'),
            'sender' => $this->f('sender'),
            'id' => $this->f('id')
        ];

        $this->validateCollection($collection);

        $this->validateNotEmptyOneOfArray($data);

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            if($data['id']) {
                $record = $database->delete($collection, $data['id']);

                $recipient = $record['recipient'] ?? null;
            }else{
                $record = $database->getRecordByRecipientSender($collection, $data['recipient'], $data['sender']);

                if(!$record){
                    return;
                }

                $data['id'] = $record['id'];
                $recipient = $data['recipient'];
            }

            if (!$badge_user) {
                $badge_user = $recipient;
            }

            if ($badge_user && $this->hasBadges()) {
                $this->getProxy()->deferCallable(function () use ($collection, $badge_user, $data) {
                    /** @var Badges $badges */
                    $badges = $this->s('badges');
                    $badges->deleteRecord($collection, $badge_user, $data['id']);
                });
            }

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();

            $this->forward('error', 'internalServerError', [$e]);
        }
    }
}
