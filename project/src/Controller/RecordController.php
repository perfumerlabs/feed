<?php


namespace Feed\Controller;

use Feed\Service\Centrifugo;
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

        /** @var Centrifugo $centrifugo */
        $centrifugo = $this->s('centrifugo');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $id = $database->insert($collection, $recipient, $data);

            if($id) {
                $data['id'] = $id;
                $data['recipient'] = $recipient;

                if ($this->getContainer()->getParam('centrifugo/endpoint')) {
                    $centrifugo->sendRecord($recipient, $data);
                }

                $this->setContent([
                    'record' => [
                        'id' => $id
                    ]
                ]);
            }

            $con->commit();
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            $con->rollBack();
        }
    }
}