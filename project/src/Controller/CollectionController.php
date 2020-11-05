<?php

namespace Feed\Controller;

use Feed\Domain\CollectionDomain;
use Feed\Service\Database;

class CollectionController extends LayoutController
{
    public function post()
    {
        $name = $this->f('name');
        $websocket_module = $this->f('websocket_module');
        $badges_collection = $this->f('badges_collection');
        $badges_prefix = $this->f('badges_prefix');

        $this->validateNotEmpty($name, 'name');
        $this->validateRegex($name, 'name', '/^[a-z0-9_]+$/');
        $this->validateRegex($websocket_module, 'websocket_module', '/^[a-z0-9_]+$/');
        $this->validateRegex($badges_collection, 'badges_collection', '/^[a-z0-9_]+$/');
        $this->validateRegex($badges_prefix, 'badges_prefix', '/^[a-z0-9_]+$/');

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            $created = $database->createTable($name);

            if ($created) {
                /** @var CollectionDomain $domain */
                $domain = $this->s('domain.collection');
                $domain->save($name, [
                    'websocket_module' => $websocket_module,
                    'badges_collection' => $badges_collection,
                    'badges_prefix' => $badges_prefix,
                ]);
            }

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }

    public function patch()
    {
        $name = $this->f('name');
        $websocket_module = $this->f('websocket_module');
        $badges_collection = $this->f('badges_collection');
        $badges_prefix = $this->f('badges_prefix');

        $this->validateNotEmpty($name, 'name');
        $this->validateRegex($name, 'name', '/^[a-z0-9_]+$/');
        $this->validateRegex($websocket_module, 'websocket_module', '/^[a-z0-9_]+$/');
        $this->validateRegex($badges_collection, 'badges_collection', '/^[a-z0-9_]+$/');
        $this->validateRegex($badges_prefix, 'badges_prefix', '/^[a-z0-9_]+$/');

        /** @var Database $database */
        $database = $this->s('database');

        $con = $database->getPdo();
        $con->beginTransaction();

        try {
            /** @var CollectionDomain $domain */
            $domain = $this->s('domain.collection');
            $domain->save($name, [
                'websocket_module' => $websocket_module,
                'badges_collection' => $badges_collection,
                'badges_prefix' => $badges_prefix,
            ]);

            $con->commit();
        } catch (\Throwable $e) {
            $this->setStatus(false);
            $con->rollBack();
        }
    }
}