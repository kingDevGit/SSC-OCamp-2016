<?php

namespace App\Model\Base;

use \Exception;
use \PDO;
use App\Model\Timer as ChildTimer;
use App\Model\TimerQuery as ChildTimerQuery;
use App\Model\Map\TimerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'chrono_timer' table.
 *
 *
 *
 * @method     ChildTimerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildTimerQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildTimerQuery orderByPause($order = Criteria::ASC) Order by the pause column
 * @method     ChildTimerQuery orderByPauseAt($order = Criteria::ASC) Order by the pause_at column
 * @method     ChildTimerQuery orderByEndAt($order = Criteria::ASC) Order by the end_at column
 * @method     ChildTimerQuery orderByClassKey($order = Criteria::ASC) Order by the class_key column
 * @method     ChildTimerQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildTimerQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildTimerQuery groupById() Group by the id column
 * @method     ChildTimerQuery groupByPlayerId() Group by the player_id column
 * @method     ChildTimerQuery groupByPause() Group by the pause column
 * @method     ChildTimerQuery groupByPauseAt() Group by the pause_at column
 * @method     ChildTimerQuery groupByEndAt() Group by the end_at column
 * @method     ChildTimerQuery groupByClassKey() Group by the class_key column
 * @method     ChildTimerQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildTimerQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildTimerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTimerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTimerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTimerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTimerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTimerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTimerQuery leftJoinPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Player relation
 * @method     ChildTimerQuery rightJoinPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Player relation
 * @method     ChildTimerQuery innerJoinPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the Player relation
 *
 * @method     ChildTimerQuery joinWithPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Player relation
 *
 * @method     ChildTimerQuery leftJoinWithPlayer() Adds a LEFT JOIN clause and with to the query using the Player relation
 * @method     ChildTimerQuery rightJoinWithPlayer() Adds a RIGHT JOIN clause and with to the query using the Player relation
 * @method     ChildTimerQuery innerJoinWithPlayer() Adds a INNER JOIN clause and with to the query using the Player relation
 *
 * @method     \App\Model\PlayerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTimer findOne(ConnectionInterface $con = null) Return the first ChildTimer matching the query
 * @method     ChildTimer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTimer matching the query, or a new ChildTimer object populated from the query conditions when no match is found
 *
 * @method     ChildTimer findOneById(int $id) Return the first ChildTimer filtered by the id column
 * @method     ChildTimer findOneByPlayerId(int $player_id) Return the first ChildTimer filtered by the player_id column
 * @method     ChildTimer findOneByPause(boolean $pause) Return the first ChildTimer filtered by the pause column
 * @method     ChildTimer findOneByPauseAt(string $pause_at) Return the first ChildTimer filtered by the pause_at column
 * @method     ChildTimer findOneByEndAt(string $end_at) Return the first ChildTimer filtered by the end_at column
 * @method     ChildTimer findOneByClassKey(string $class_key) Return the first ChildTimer filtered by the class_key column
 * @method     ChildTimer findOneByCreatedAt(string $created_at) Return the first ChildTimer filtered by the created_at column
 * @method     ChildTimer findOneByUpdatedAt(string $updated_at) Return the first ChildTimer filtered by the updated_at column *

 * @method     ChildTimer requirePk($key, ConnectionInterface $con = null) Return the ChildTimer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOne(ConnectionInterface $con = null) Return the first ChildTimer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTimer requireOneById(int $id) Return the first ChildTimer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByPlayerId(int $player_id) Return the first ChildTimer filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByPause(boolean $pause) Return the first ChildTimer filtered by the pause column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByPauseAt(string $pause_at) Return the first ChildTimer filtered by the pause_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByEndAt(string $end_at) Return the first ChildTimer filtered by the end_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByClassKey(string $class_key) Return the first ChildTimer filtered by the class_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByCreatedAt(string $created_at) Return the first ChildTimer filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTimer requireOneByUpdatedAt(string $updated_at) Return the first ChildTimer filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTimer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTimer objects based on current ModelCriteria
 * @method     ChildTimer[]|ObjectCollection findById(int $id) Return ChildTimer objects filtered by the id column
 * @method     ChildTimer[]|ObjectCollection findByPlayerId(int $player_id) Return ChildTimer objects filtered by the player_id column
 * @method     ChildTimer[]|ObjectCollection findByPause(boolean $pause) Return ChildTimer objects filtered by the pause column
 * @method     ChildTimer[]|ObjectCollection findByPauseAt(string $pause_at) Return ChildTimer objects filtered by the pause_at column
 * @method     ChildTimer[]|ObjectCollection findByEndAt(string $end_at) Return ChildTimer objects filtered by the end_at column
 * @method     ChildTimer[]|ObjectCollection findByClassKey(string $class_key) Return ChildTimer objects filtered by the class_key column
 * @method     ChildTimer[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildTimer objects filtered by the created_at column
 * @method     ChildTimer[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildTimer objects filtered by the updated_at column
 * @method     ChildTimer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TimerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \App\Model\Base\TimerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'chrono', $modelName = '\\App\\Model\\Timer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTimerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTimerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTimerQuery) {
            return $criteria;
        }
        $query = new ChildTimerQuery();
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
     * @return ChildTimer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TimerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TimerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTimer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, player_id, pause, pause_at, end_at, class_key, created_at, updated_at FROM chrono_timer WHERE id = :p0';
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
            $cls = TimerTableMap::getOMClass($row, 0, false);
            /** @var ChildTimer $obj */
            $obj = new $cls();
            $obj->hydrate($row);
            TimerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTimer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TimerTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TimerTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TimerTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TimerTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the player_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerId(1234); // WHERE player_id = 1234
     * $query->filterByPlayerId(array(12, 34)); // WHERE player_id IN (12, 34)
     * $query->filterByPlayerId(array('min' => 12)); // WHERE player_id > 12
     * </code>
     *
     * @see       filterByPlayer()
     *
     * @param     mixed $playerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(TimerTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(TimerTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_PLAYER_ID, $playerId, $comparison);
    }

    /**
     * Filter the query on the pause column
     *
     * Example usage:
     * <code>
     * $query->filterByPause(true); // WHERE pause = true
     * $query->filterByPause('yes'); // WHERE pause = true
     * </code>
     *
     * @param     boolean|string $pause The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByPause($pause = null, $comparison = null)
    {
        if (is_string($pause)) {
            $pause = in_array(strtolower($pause), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TimerTableMap::COL_PAUSE, $pause, $comparison);
    }

    /**
     * Filter the query on the pause_at column
     *
     * Example usage:
     * <code>
     * $query->filterByPauseAt('2011-03-14'); // WHERE pause_at = '2011-03-14'
     * $query->filterByPauseAt('now'); // WHERE pause_at = '2011-03-14'
     * $query->filterByPauseAt(array('max' => 'yesterday')); // WHERE pause_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $pauseAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByPauseAt($pauseAt = null, $comparison = null)
    {
        if (is_array($pauseAt)) {
            $useMinMax = false;
            if (isset($pauseAt['min'])) {
                $this->addUsingAlias(TimerTableMap::COL_PAUSE_AT, $pauseAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pauseAt['max'])) {
                $this->addUsingAlias(TimerTableMap::COL_PAUSE_AT, $pauseAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_PAUSE_AT, $pauseAt, $comparison);
    }

    /**
     * Filter the query on the end_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEndAt('2011-03-14'); // WHERE end_at = '2011-03-14'
     * $query->filterByEndAt('now'); // WHERE end_at = '2011-03-14'
     * $query->filterByEndAt(array('max' => 'yesterday')); // WHERE end_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $endAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByEndAt($endAt = null, $comparison = null)
    {
        if (is_array($endAt)) {
            $useMinMax = false;
            if (isset($endAt['min'])) {
                $this->addUsingAlias(TimerTableMap::COL_END_AT, $endAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endAt['max'])) {
                $this->addUsingAlias(TimerTableMap::COL_END_AT, $endAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_END_AT, $endAt, $comparison);
    }

    /**
     * Filter the query on the class_key column
     *
     * Example usage:
     * <code>
     * $query->filterByClassKey('fooValue');   // WHERE class_key = 'fooValue'
     * $query->filterByClassKey('%fooValue%'); // WHERE class_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $classKey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByClassKey($classKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($classKey)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_CLASS_KEY, $classKey, $comparison);
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
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TimerTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TimerTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(TimerTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(TimerTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TimerTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \App\Model\Player object
     *
     * @param \App\Model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTimerQuery The current query, for fluid interface
     */
    public function filterByPlayer($player, $comparison = null)
    {
        if ($player instanceof \App\Model\Player) {
            return $this
                ->addUsingAlias(TimerTableMap::COL_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TimerTableMap::COL_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayer() only accepts arguments of type \App\Model\Player or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Player relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function joinPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Player');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Player');
        }

        return $this;
    }

    /**
     * Use the Player relation Player object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\PlayerQuery A secondary query class using the current class as primary query
     */
    public function usePlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Player', '\App\Model\PlayerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTimer $timer Object to remove from the list of results
     *
     * @return $this|ChildTimerQuery The current query, for fluid interface
     */
    public function prune($timer = null)
    {
        if ($timer) {
            $this->addUsingAlias(TimerTableMap::COL_ID, $timer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the chrono_timer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TimerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TimerTableMap::clearInstancePool();
            TimerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TimerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TimerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TimerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TimerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildTimerQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(TimerTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildTimerQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(TimerTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildTimerQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(TimerTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildTimerQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(TimerTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildTimerQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(TimerTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildTimerQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(TimerTableMap::COL_CREATED_AT);
    }

} // TimerQuery
