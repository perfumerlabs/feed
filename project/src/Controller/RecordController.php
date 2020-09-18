<?php


namespace Feed\Controller;

use Feed\Service\Database;
use Perfumer\Helper\Arr;

class RecordController extends LayoutController
{
    public function post()
    {
        $collection = $this->f('collection');
        $recipient = $this->f('recipient');

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

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $id = $database->insert($collection, $recipient, $data);

            $this->setContent([
                'record' => [
                    'id' => $id
                ]
            ]);

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollBack();
        }
    }
}