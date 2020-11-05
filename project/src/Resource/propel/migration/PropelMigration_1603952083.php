<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1603952083.
 * Generated on 2020-10-29 06:14:43 by feed
 */
class PropelMigration_1603952083
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
            'feed' => '
BEGIN;

ALTER TABLE "feed_collection"

  ADD "websocket_module" VARCHAR(255),

  ADD "badges_collection" VARCHAR(255),

  ADD "badges_prefix" VARCHAR(255);

COMMIT;
',
        );
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
            'feed' => '
BEGIN;

ALTER TABLE "feed_collection"

  DROP COLUMN "websocket_module",

  DROP COLUMN "badges_collection",

  DROP COLUMN "badges_prefix";

COMMIT;
',
        );
    }

}