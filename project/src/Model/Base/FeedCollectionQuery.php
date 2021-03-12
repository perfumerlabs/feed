<?php

namespace Feed\Model\Base;

use \Exception;
use \PDO;
use Feed\Model\FeedCollection as ChildFeedCollection;
use Feed\Model\FeedCollectionQuery as ChildFeedCollectionQuery;
use Feed\Model\Map\FeedCollectionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'feed_collection' table.
 *
 *
 *
 * @method     ChildFeedCollectionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildFeedCollectionQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildFeedCollectionQuery orderByWebsocketModule($order = Criteria::ASC) Order by the websocket_module column
 * @method     ChildFeedCollectionQuery orderByBadgesCollection($order = Criteria::ASC) Order by the badges_collection column
 * @method     ChildFeedCollectionQuery orderByBadgesPrefix($order = Criteria::ASC) Order by the badges_prefix column
 * @method     ChildFeedCollectionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 *
 * @method     ChildFeedCollectionQuery groupById() Group by the id column
 * @method     ChildFeedCollectionQuery groupByName() Group by the name column
 * @method     ChildFeedCollectionQuery groupByWebsocketModule() Group by the websocket_module column
 * @method     ChildFeedCollectionQuery groupByBadgesCollection() Group by the badges_collection column
 * @method     ChildFeedCollectionQuery groupByBadgesPrefix() Group by the badges_prefix column
 * @method     ChildFeedCollectionQuery groupByCreatedAt() Group by the created_at column
 *
 * @method     ChildFeedCollectionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFeedCollectionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFeedCollectionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFeedCollectionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFeedCollectionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFeedCollectionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFeedCollection|null findOne(ConnectionInterface $con = null) Return the first ChildFeedCollection matching the query
 * @method     ChildFeedCollection findOneOrCreate(ConnectionInterface $con = null) Return the first ChildFeedCollection matching the query, or a new ChildFeedCollection object populated from the query conditions when no match is found
 *
 * @method     ChildFeedCollection|null findOneById(int $id) Return the first ChildFeedCollection filtered by the id column
 * @method     ChildFeedCollection|null findOneByName(string $name) Return the first ChildFeedCollection filtered by the name column
 * @method     ChildFeedCollection|null findOneByWebsocketModule(string $websocket_module) Return the first ChildFeedCollection filtered by the websocket_module column
 * @method     ChildFeedCollection|null findOneByBadgesCollection(string $badges_collection) Return the first ChildFeedCollection filtered by the badges_collection column
 * @method     ChildFeedCollection|null findOneByBadgesPrefix(string $badges_prefix) Return the first ChildFeedCollection filtered by the badges_prefix column
 * @method     ChildFeedCollection|null findOneByCreatedAt(string $created_at) Return the first ChildFeedCollection filtered by the created_at column *

 * @method     ChildFeedCollection requirePk($key, ConnectionInterface $con = null) Return the ChildFeedCollection by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedCollection requireOne(ConnectionInterface $con = null) Return the first ChildFeedCollection matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFeedCollection requireOneById(int $id) Return the first ChildFeedCollection filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedCollection requireOneByName(string $name) Return the first ChildFeedCollection filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedCollection requireOneByWebsocketModule(string $websocket_module) Return the first ChildFeedCollection filtered by the websocket_module column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedCollection requireOneByBadgesCollection(string $badges_collection) Return the first ChildFeedCollection filtered by the badges_collection column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedCollection requireOneByBadgesPrefix(string $badges_prefix) Return the first ChildFeedCollection filtered by the badges_prefix column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFeedCollection requireOneByCreatedAt(string $created_at) Return the first ChildFeedCollection filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFeedCollection[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildFeedCollection objects based on current ModelCriteria
 * @method     ChildFeedCollection[]|ObjectCollection findById(int $id) Return ChildFeedCollection objects filtered by the id column
 * @method     ChildFeedCollection[]|ObjectCollection findByName(string $name) Return ChildFeedCollection objects filtered by the name column
 * @method     ChildFeedCollection[]|ObjectCollection findByWebsocketModule(string $websocket_module) Return ChildFeedCollection objects filtered by the websocket_module column
 * @method     ChildFeedCollection[]|ObjectCollection findByBadgesCollection(string $badges_collection) Return ChildFeedCollection objects filtered by the badges_collection column
 * @method     ChildFeedCollection[]|ObjectCollection findByBadgesPrefix(string $badges_prefix) Return ChildFeedCollection objects filtered by the badges_prefix column
 * @method     ChildFeedCollection[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildFeedCollection objects filtered by the created_at column
 * @method     ChildFeedCollection[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FeedCollectionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Feed\Model\Base\FeedCollectionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'feed', $modelName = '\\Feed\\Model\\FeedCollection', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFeedCollectionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFeedCollectionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildFeedCollectionQuery) {
            return $criteria;
        }
        $query = new ChildFeedCollectionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildFeedCollection|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FeedCollectionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FeedCollectionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFeedCollection A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, websocket_module, badges_collection, badges_prefix, created_at FROM feed_collection WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildFeedCollection $obj */
            $obj = new ChildFeedCollection();
            $obj->hydrate($row);
            FeedCollectionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildFeedCollection|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FeedCollectionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FeedCollectionTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FeedCollectionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FeedCollectionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedCollectionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedCollectionTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the websocket_module column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsocketModule('fooValue');   // WHERE websocket_module = 'fooValue'
     * $query->filterByWebsocketModule('%fooValue%', Criteria::LIKE); // WHERE websocket_module LIKE '%fooValue%'
     * </code>
     *
     * @param     string $websocketModule The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByWebsocketModule($websocketModule = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($websocketModule)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedCollectionTableMap::COL_WEBSOCKET_MODULE, $websocketModule, $comparison);
    }

    /**
     * Filter the query on the badges_collection column
     *
     * Example usage:
     * <code>
     * $query->filterByBadgesCollection('fooValue');   // WHERE badges_collection = 'fooValue'
     * $query->filterByBadgesCollection('%fooValue%', Criteria::LIKE); // WHERE badges_collection LIKE '%fooValue%'
     * </code>
     *
     * @param     string $badgesCollection The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByBadgesCollection($badgesCollection = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($badgesCollection)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedCollectionTableMap::COL_BADGES_COLLECTION, $badgesCollection, $comparison);
    }

    /**
     * Filter the query on the badges_prefix column
     *
     * Example usage:
     * <code>
     * $query->filterByBadgesPrefix('fooValue');   // WHERE badges_prefix = 'fooValue'
     * $query->filterByBadgesPrefix('%fooValue%', Criteria::LIKE); // WHERE badges_prefix LIKE '%fooValue%'
     * </code>
     *
     * @param     string $badgesPrefix The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByBadgesPrefix($badgesPrefix = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($badgesPrefix)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedCollectionTableMap::COL_BADGES_PREFIX, $badgesPrefix, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FeedCollectionTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FeedCollectionTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeedCollectionTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildFeedCollection $feedCollection Object to remove from the list of results
     *
     * @return $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function prune($feedCollection = null)
    {
        if ($feedCollection) {
            $this->addUsingAlias(FeedCollectionTableMap::COL_ID, $feedCollection->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the feed_collection table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedCollectionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FeedCollectionTableMap::clearInstancePool();
            FeedCollectionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FeedCollectionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FeedCollectionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FeedCollectionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FeedCollectionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Order by create date desc
     *
     * @return     $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FeedCollectionTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FeedCollectionTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildFeedCollectionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FeedCollectionTableMap::COL_CREATED_AT);
    }

} // FeedCollectionQuery
