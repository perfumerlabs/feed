<?php

namespace Feed\Controller\Records;

use Feed\Controller\LayoutController;
use Feed\Service\Badges;
use Feed\Service\Database;

class CountController extends LayoutController
{
    public function get()
    {
        $collection = $this->f('collection');
        $recipient = (string) $this->f('recipient');
        $where = $this->f('where');
        $group = $this->f('group');

        $this->validateCollection($collection);
        $this->validateNotEmpty($recipient, 'recipient');

        /** @var Database $database */
        $database = $this->s('database');

        try {
            $result = $database->getRecordsCount($collection, $recipient, $where, $group);

        } catch (\Throwable $e) {
            $this->setStatus(false);
        }

        $this->setContent($result);
    }
}