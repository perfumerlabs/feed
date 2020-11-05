<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1600371135.
 * Generated on 2020-09-17 19:32:15 by root
 */
class PropelMigration_1600371135
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

CREATE TABLE IF NOT EXISTS "feed_collection"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    "created_at" TIMESTAMP,
    PRIMARY KEY ("id"),
    CONSTRAINT "feed_collection_u_d94269" UNIQUE ("name")
);

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

DROP TABLE IF EXISTS "feed_collection" CASCADE;

COMMIT;
',
);
    }

}