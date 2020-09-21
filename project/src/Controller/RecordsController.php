<?php


namespace Feed\Controller;

use Feed\Service\Database;
use Perfumer\Helper\Arr;

class RecordsController extends LayoutController
{
    public function get()
    {
        $collection = $this->f('collection');

        $this->validateCollection($collection);

        $sender = $this->f('sender');
        $thread = $this->f('thread');
        $recipient = $this->f('recipient');
        $id = $this->f('id');
        $limit = $this->f('limit');

        /** @var Database $database */
        $database = $this->s('database');

        $records = $database->getRecords($collection, $recipient, $sender, $thread, $id, $limit);

        $this->setContent(['records' => $records]);
    }
}