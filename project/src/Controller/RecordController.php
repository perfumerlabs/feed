<?php


namespace Feed\Controller;

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
        $badge_collection = $this->f('badge_collection');
        $badge_user = $this->f('badge_user');

        $this->validateCollection($collection);
        $this->validateNotEmpty($recipient, 'recipient');

        $data = Arr::fetch($this->f(), [
            'sender',
            'thread',
            'title',
            'text',
            'image',
            'payload'
        ]);

        /** @var Database $database */
        $database = $this->s('database');

        /** @var Centrifugo $centrifugo */
        $centrifugo = $this->s('centrifugo');

        /** @var Badges $badges */
        $badges = $this->s('badges');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $id = $database->insert($collection, $recipient, $data);

            if($id) {
                $data['id'] = $id;
                $data['recipient'] = $recipient;

                if ($this->hasCentrifugo()) {
                    $centrifugo->sendRecord($recipient, $data);
                }

                if($this->hasBadges() && $badge_collection){
                    $badges->addRecord($badge_collection, ($badge_user ?: $recipient), $id);
                }

                $this->setContent([
                    'record' => [
                        'id' => $id
                    ]
                ]);
            }

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }
}