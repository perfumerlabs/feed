<?php

namespace Feed\Model\Map;

use Feed\Model\FeedCollection;
use Feed\Model\FeedCollectionQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'feed_collection' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class FeedCollectionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.FeedCollectionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'feed';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'feed_collection';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Feed\\Model\\FeedCollection';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'FeedCollection';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'feed_collection.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'feed_collection.name';

    /**
     * the column name for the websocket_module field
     */
    const COL_WEBSOCKET_MODULE = 'feed_collection.websocket_module';

    /**
     * the column name for the badges_collection field
     */
    const COL_BADGES_COLLECTION = 'feed_collection.badges_collection';

    /**
     * the column name for the badges_prefix field
     */
    const COL_BADGES_PREFIX = 'feed_collection.badges_prefix';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'feed_collection.created_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'WebsocketModule', 'BadgesCollection', 'BadgesPrefix', 'CreatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'websocketModule', 'badgesCollection', 'badgesPrefix', 'createdAt', ),
        self::TYPE_COLNAME       => array(FeedCollectionTableMap::COL_ID, FeedCollectionTableMap::COL_NAME, FeedCollectionTableMap::COL_WEBSOCKET_MODULE, FeedCollectionTableMap::COL_BADGES_COLLECTION, FeedCollectionTableMap::COL_BADGES_PREFIX, FeedCollectionTableMap::COL_CREATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'websocket_module', 'badges_collection', 'badges_prefix', 'created_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'WebsocketModule' => 2, 'BadgesCollection' => 3, 'BadgesPrefix' => 4, 'CreatedAt' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'websocketModule' => 2, 'badgesCollection' => 3, 'badgesPrefix' => 4, 'createdAt' => 5, ),
        self::TYPE_COLNAME       => array(FeedCollectionTableMap::COL_ID => 0, FeedCollectionTableMap::COL_NAME => 1, FeedCollectionTableMap::COL_WEBSOCKET_MODULE => 2, FeedCollectionTableMap::COL_BADGES_COLLECTION => 3, FeedCollectionTableMap::COL_BADGES_PREFIX => 4, FeedCollectionTableMap::COL_CREATED_AT => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'websocket_module' => 2, 'badges_collection' => 3, 'badges_prefix' => 4, 'created_at' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('feed_collection');
        $this->setPhpName('FeedCollection');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Feed\\Model\\FeedCollection');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('feed_collection_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('websocket_module', 'WebsocketModule', 'VARCHAR', false, 255, null);
        $this->addColumn('badges_collection', 'BadgesCollection', 'VARCHAR', false, 255, null);
        $this->addColumn('badges_prefix', 'BadgesPrefix', 'VARCHAR', false, 255, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'true', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? FeedCollectionTableMap::CLASS_DEFAULT : FeedCollectionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (FeedCollection object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = FeedCollectionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FeedCollectionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FeedCollectionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FeedCollectionTableMap::OM_CLASS;
            /** @var FeedCollection $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FeedCollectionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = FeedCollectionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FeedCollectionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var FeedCollection $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FeedCollectionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FeedCollectionTableMap::COL_ID);
            $criteria->addSelectColumn(FeedCollectionTableMap::COL_NAME);
            $criteria->addSelectColumn(FeedCollectionTableMap::COL_WEBSOCKET_MODULE);
            $criteria->addSelectColumn(FeedCollectionTableMap::COL_BADGES_COLLECTION);
            $criteria->addSelectColumn(FeedCollectionTableMap::COL_BADGES_PREFIX);
            $criteria->addSelectColumn(FeedCollectionTableMap::COL_CREATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.websocket_module');
            $criteria->addSelectColumn($alias . '.badges_collection');
            $criteria->addSelectColumn($alias . '.badges_prefix');
            $criteria->addSelectColumn($alias . '.created_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(FeedCollectionTableMap::DATABASE_NAME)->getTable(FeedCollectionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(FeedCollectionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(FeedCollectionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new FeedCollectionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a FeedCollection or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or FeedCollection object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedCollectionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Feed\Model\FeedCollection) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FeedCollectionTableMap::DATABASE_NAME);
            $criteria->add(FeedCollectionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = FeedCollectionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FeedCollectionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FeedCollectionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the feed_collection table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return FeedCollectionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a FeedCollection or Criteria object.
     *
     * @param mixed               $criteria Criteria or FeedCollection object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedCollectionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from FeedCollection object
        }

        if ($criteria->containsKey(FeedCollectionTableMap::COL_ID) && $criteria->keyContainsValue(FeedCollectionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FeedCollectionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = FeedCollectionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // FeedCollectionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
FeedCollectionTableMap::buildTableMap();
