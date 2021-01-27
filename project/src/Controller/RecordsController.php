<?php


namespace Feed\Controller;

use Feed\Repository\RecordRepository;
use Feed\Service\Badges;
use Feed\Service\Centrifugo;
use Feed\Service\Database;
use Perfumer\Helper\Arr;

class RecordsController extends LayoutController
{
    public function get()
    {
        $collection = $this->f('collection');

        $this->validateCollection($collection);

        $sender = $this->f('sender');
        $user = $this->f('user');
        $thread = $this->f('thread');
        $recipient = $this->f('recipient');
        $search = $this->f('search');
        $id = $this->f('id');
        $limit = $this->f('limit');
        $offset = $this->f('offset');
        $order = $this->f('order', 'desc');
        $is_read = $this->f('is_read');

        /** @var Database $database */
        $database = $this->s('database');

        $records = $database->getRecords([
            'collection' => $collection,
            'recipient' => $recipient,
            'sender' => $sender,
            'thread' => $thread,
            'user'   => $user,
            'search' => $search,
            'id' => $id,
            'limit' => $limit,
            'order' => $order,
            'is_read' => $is_read,
            'offset' => $offset,
        ]);

        /** @var RecordRepository $repository */
        $repository = $this->s('repository.record');

        $this->setContent([
            'records' => $repository->formatCollection($records)
        ]);
    }

    public function post()
    {
        $collection = $this->f('collection');
        $recipients = (array) $this->f('recipients');
        $records = (array) $this->f('records');

        $this->validateCollection($collection);
        $this->validateNotEmpty($recipients, 'recipients');
        $this->validateNotEmpty($records, 'records');

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $inserted_ids = $database->insertMultiple($collection, $recipients, $records);

            if($inserted_ids) {
                $this->setContent([
                    'ids' => $inserted_ids
                ]);
            }

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();

            $this->forward('error', 'internalServerError', [$e]);
        }
    }

    public function delete()
    {
        $collection = $this->f('collection');
        $badge_user = $this->f('badge_user');

        $data = [
            'recipient' => $this->f('recipient'),
            'sender' => $this->f('sender'),
            'thread' => $this->f('thread')
        ];

        $this->validateCollection($collection);
        $this->validateNotEmptyOneOfArray($data);

        /** @var Database $database */
        $database = $this->s('database');

        if(!$data['thread']) {
            $deleted = $database->deleteAll($collection, $data);
        }else{
            if(is_array($data['thread'])){
                $data['thread'] = '\'' . implode('\',\'', $data['thread']) . '\'';
            }

            $deleted = $database->deleteAllByThread($collection, $data['recipient'], $data['thread']);
        }

        if ($deleted && $this->hasBadges() && $data['recipient']){
            if (!$badge_user) {
                $badge_user = $data['recipient'];
            }

            $this->getProxy()->deferCallable(function () use ($collection, $badge_user) {
                /** @var Badges $badges */
                $badges = $this->s('badges');
                $badges->deleteAll($collection, $badge_user);
            });
        }
    }

    public function patch()
    {
        $collection = $this->f('collection');
        $where = $this->f('where');
        $set = $this->f('set');

        $this->validateCollection($collection);
        $this->validateNotEmpty($where, 'where');
        $this->validateNotEmpty($set, 'set');

        /** @var Database $database */
        $database = $this->s('database');
        $database->update($collection, $where, $set);
    }
}
