<?php


namespace Feed\Controller;

use Feed\Service\Database;
use Perfumer\Helper\Arr;

class RecordsController extends LayoutController
{
    public function get()
    {
        $collection = $this->f('collection');
        $recipient = $this->f('recipient');

        $this->validateCollection($collection);
        $this->validateNotEmpty($recipient, 'recipient');

        $sender = $this->f('sender');
        $thread = $this->f('thread');
        $id = $this->f('id');
        $limit = $this->f('limit');

        /** @var Database $database */
        $database = $this->s('database');

        $records = $database->getRecords($collection, $recipient, $sender, $thread, $id, $limit);

        $this->setContent(['records' => $records]);
    }
}