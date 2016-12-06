<?php

namespace App\Model\Base;

use \Exception;
use \PDO;
use App\Model\Player as ChildPlayer;
use App\Model\PlayerQuery as ChildPlayerQuery;
use App\Model\Map\PlayerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'chrono_player' table.
 *
 *
 *
 * @method     ChildPlayerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerQuery orderByNickname($order = Criteria::ASC) Order by the nickname column
 * @method     ChildPlayerQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method     ChildPlayerQuery orderByUnionId($order = Criteria::ASC) Order by the union_id column
 * @method     ChildPlayerQuery orderByTags($order = Criteria::ASC) Order by the tags column
 * @method     ChildPlayerQuery orderByAddress($order = Criteria::ASC) Order by the address column
 * @method     ChildPlayerQuery orderByDieCount($order = Criteria::ASC) Order by the die_count column
 * @method     ChildPlayerQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPlayerQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPlayerQuery groupById() Group by the id column
 * @method     ChildPlayerQuery groupByNickname() Group by the nickname column
 * @method     ChildPlayerQuery groupByGender() Group by the gender column
 * @method     ChildPlayerQuery groupByUnionId() Group by the union_id column
 * @method     ChildPlayerQuery groupByTags() Group by the tags column
 * @method     ChildPlayerQuery groupByAddress() Group by the address column
 * @method     ChildPlayerQuery groupByDieCount() Group by the die_count column
 * @method     ChildPlayerQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPlayerQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPlayerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerQuery leftJoinUnion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Union relation
 * @method     ChildPlayerQuery rightJoinUnion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Union relation
 * @method     ChildPlayerQuery innerJoinUnion($relationAlias = null) Adds a INNER JOIN clause to the query using the Union relation
 *
 * @method     ChildPlayerQuery joinWithUnion($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Union relation
 *
 * @method     ChildPlayerQuery leftJoinWithUnion() Adds a LEFT JOIN clause and with to the query using the Union relation
 * @method     ChildPlayerQuery rightJoinWithUnion() Adds a RIGHT JOIN clause and with to the query using the Union relation
 * @method     ChildPlayerQuery innerJoinWithUnion() Adds a INNER JOIN clause and with to the query using the Union relation
 *
 * @method     ChildPlayerQuery leftJoinTimer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Timer relation
 * @method     ChildPlayerQuery rightJoinTimer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Timer relation
 * @method     ChildPlayerQuery innerJoinTimer($relationAlias = null) Adds a INNER JOIN clause to the query using the Timer relation
 *
 * @method     ChildPlayerQuery joinWithTimer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Timer relation
 *
 * @method     ChildPlayerQuery leftJoinWithTimer() Adds a LEFT JOIN clause and with to the query using the Timer relation
 * @method     ChildPlayerQuery rightJoinWithTimer() Adds a RIGHT JOIN clause and with to the query using the Timer relation
 * @method     ChildPlayerQuery innerJoinWithTimer() Adds a INNER JOIN clause and with to the query using the Timer relation
 *
 * @method     ChildPlayerQuery leftJoinTransactionRelatedByPlayerA($relationAlias = null) Adds a LEFT JOIN clause to the query using the TransactionRelatedByPlayerA relation
 * @method     ChildPlayerQuery rightJoinTransactionRelatedByPlayerA($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TransactionRelatedByPlayerA relation
 * @method     ChildPlayerQuery innerJoinTransactionRelatedByPlayerA($relationAlias = null) Adds a INNER JOIN clause to the query using the TransactionRelatedByPlayerA relation
 *
 * @method     ChildPlayerQuery joinWithTransactionRelatedByPlayerA($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TransactionRelatedByPlayerA relation
 *
 * @method     ChildPlayerQuery leftJoinWithTransactionRelatedByPlayerA() Adds a LEFT JOIN clause and with to the query using the TransactionRelatedByPlayerA relation
 * @method     ChildPlayerQuery rightJoinWithTransactionRelatedByPlayerA() Adds a RIGHT JOIN clause and with to the query using the TransactionRelatedByPlayerA relation
 * @method     ChildPlayerQuery innerJoinWithTransactionRelatedByPlayerA() Adds a INNER JOIN clause and with to the query using the TransactionRelatedByPlayerA relation
 *
 * @method     ChildPlayerQuery leftJoinTransactionRelatedByPlayerB($relationAlias = null) Adds a LEFT JOIN clause to the query using the TransactionRelatedByPlayerB relation
 * @method     ChildPlayerQuery rightJoinTransactionRelatedByPlayerB($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TransactionRelatedByPlayerB relation
 * @method     ChildPlayerQuery innerJoinTransactionRelatedByPlayerB($relationAlias = null) Adds a INNER JOIN clause to the query using the TransactionRelatedByPlayerB relation
 *
 * @method     ChildPlayerQuery joinWithTransactionRelatedByPlayerB($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TransactionRelatedByPlayerB relation
 *
 * @method     ChildPlayerQuery leftJoinWithTransactionRelatedByPlayerB() Adds a LEFT JOIN clause and with to the query using the TransactionRelatedByPlayerB relation
 * @method     ChildPlayerQuery rightJoinWithTransactionRelatedByPlayerB() Adds a RIGHT JOIN clause and with to the query using the TransactionRelatedByPlayerB relation
 * @method     ChildPlayerQuery innerJoinWithTransactionRelatedByPlayerB() Adds a INNER JOIN clause and with to the query using the TransactionRelatedByPlayerB relation
 *
 * @method     ChildPlayerQuery leftJoinNotification($relationAlias = null) Adds a LEFT JOIN clause to the query using the Notification relation
 * @method     ChildPlayerQuery rightJoinNotification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Notification relation
 * @method     ChildPlayerQuery innerJoinNotification($relationAlias = null) Adds a INNER JOIN clause to the query using the Notification relation
 *
 * @method     ChildPlayerQuery joinWithNotification($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Notification relation
 *
 * @method     ChildPlayerQuery leftJoinWithNotification() Adds a LEFT JOIN clause and with to the query using the Notification relation
 * @method     ChildPlayerQuery rightJoinWithNotification() Adds a RIGHT JOIN clause and with to the query using the Notification relation
 * @method     ChildPlayerQuery innerJoinWithNotification() Adds a INNER JOIN clause and with to the query using the Notification relation
 *
 * @method     ChildPlayerQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method     ChildPlayerQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method     ChildPlayerQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method     ChildPlayerQuery joinWithAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Account relation
 *
 * @method     ChildPlayerQuery leftJoinWithAccount() Adds a LEFT JOIN clause and with to the query using the Account relation
 * @method     ChildPlayerQuery rightJoinWithAccount() Adds a RIGHT JOIN clause and with to the query using the Account relation
 * @method     ChildPlayerQuery innerJoinWithAccount() Adds a INNER JOIN clause and with to the query using the Account relation
 *
 * @method     ChildPlayerQuery leftJoinSession($relationAlias = null) Adds a LEFT JOIN clause to the query using the Session relation
 * @method     ChildPlayerQuery rightJoinSession($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Session relation
 * @method     ChildPlayerQuery innerJoinSession($relationAlias = null) Adds a INNER JOIN clause to the query using the Session relation
 *
 * @method     ChildPlayerQuery joinWithSession($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Session relation
 *
 * @method     ChildPlayerQuery leftJoinWithSession() Adds a LEFT JOIN clause and with to the query using the Session relation
 * @method     ChildPlayerQuery rightJoinWithSession() Adds a RIGHT JOIN clause and with to the query using the Session relation
 * @method     ChildPlayerQuery innerJoinWithSession() Adds a INNER JOIN clause and with to the query using the Session relation
 *
 * @method     \App\Model\UnionQuery|\App\Model\TimerQuery|\App\Model\TransactionQuery|\App\Model\NotificationQuery|\App\Model\AccountQuery|\App\Model\SessionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayer findOne(ConnectionInterface $con = null) Return the first ChildPlayer matching the query
 * @method     ChildPlayer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayer matching the query, or a new ChildPlayer object populated from the query conditions when no match is found
 *
 * @method     ChildPlayer findOneById(int $id) Return the first ChildPlayer filtered by the id column
 * @method     ChildPlayer findOneByNickname(string $nickname) Return the first ChildPlayer filtered by the nickname column
 * @method     ChildPlayer findOneByGender(int $gender) Return the first ChildPlayer filtered by the gender column
 * @method     ChildPlayer findOneByUnionId(int $union_id) Return the first ChildPlayer filtered by the union_id column
 * @method     ChildPlayer findOneByTags(array $tags) Return the first ChildPlayer filtered by the tags column
 * @method     ChildPlayer findOneByAddress(string $address) Return the first ChildPlayer filtered by the address column
 * @method     ChildPlayer findOneByDieCount(int $die_count) Return the first ChildPlayer filtered by the die_count column
 * @method     ChildPlayer findOneByCreatedAt(string $created_at) Return the first ChildPlayer filtered by the created_at column
 * @method     ChildPlayer findOneByUpdatedAt(string $updated_at) Return the first ChildPlayer filtered by the updated_at column *

 * @method     ChildPlayer requirePk($key, ConnectionInterface $con = null) Return the ChildPlayer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOne(ConnectionInterface $con = null) Return the first ChildPlayer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayer requireOneById(int $id) Return the first ChildPlayer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByNickname(string $nickname) Return the first ChildPlayer filtered by the nickname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByGender(int $gender) Return the first ChildPlayer filtered by the gender column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByUnionId(int $union_id) Return the first ChildPlayer filtered by the union_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByTags(array $tags) Return the first ChildPlayer filtered by the tags column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByAddress(string $address) Return the first ChildPlayer filtered by the address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByDieCount(int $die_count) Return the first ChildPlayer filtered by the die_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByCreatedAt(string $created_at) Return the first ChildPlayer filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByUpdatedAt(string $updated_at) Return the first ChildPlayer filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayer objects based on current ModelCriteria
 * @method     ChildPlayer[]|ObjectCollection findById(int $id) Return ChildPlayer objects filtered by the id column
 * @method     ChildPlayer[]|ObjectCollection findByNickname(string $nickname) Return ChildPlayer objects filtered by the nickname column
 * @method     ChildPlayer[]|ObjectCollection findByGender(int $gender) Return ChildPlayer objects filtered by the gender column
 * @method     ChildPlayer[]|ObjectCollection findByUnionId(int $union_id) Return ChildPlayer objects filtered by the union_id column
 * @method     ChildPlayer[]|ObjectCollection findByTags(array $tags) Return ChildPlayer objects filtered by the tags column
 * @method     ChildPlayer[]|ObjectCollection findByAddress(string $address) Return ChildPlayer objects filtered by the address column
 * @method     ChildPlayer[]|ObjectCollection findByDieCount(int $die_count) Return ChildPlayer objects filtered by the die_count column
 * @method     ChildPlayer[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPlayer objects filtered by the created_at column
 * @method     ChildPlayer[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPlayer objects filtered by the updated_at column
 * @method     ChildPlayer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \App\Model\Base\PlayerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'chrono', $modelName = '\\App\\Model\\Player', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerQuery) {
            return $criteria;
        }
        $query = new ChildPlayerQuery();
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
     * @return ChildPlayer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, nickname, gender, union_id, tags, address, die_count, created_at, updated_at FROM chrono_player WHERE id = :p0';
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
            /** @var ChildPlayer $obj */
            $obj = new ChildPlayer();
            $obj->hydrate($row);
            PlayerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the nickname column
     *
     * Example usage:
     * <code>
     * $query->filterByNickname('fooValue');   // WHERE nickname = 'fooValue'
     * $query->filterByNickname('%fooValue%'); // WHERE nickname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nickname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByNickname($nickname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nickname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_NICKNAME, $nickname, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        $valueSet = PlayerTableMap::getValueSet(PlayerTableMap::COL_GENDER);
        if (is_scalar($gender)) {
            if (!in_array($gender, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $gender));
            }
            $gender = array_search($gender, $valueSet);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_GENDER, $gender, $comparison);
    }

    /**
     * Filter the query on the union_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUnionId(1234); // WHERE union_id = 1234
     * $query->filterByUnionId(array(12, 34)); // WHERE union_id IN (12, 34)
     * $query->filterByUnionId(array('min' => 12)); // WHERE union_id > 12
     * </code>
     *
     * @see       filterByUnion()
     *
     * @param     mixed $unionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByUnionId($unionId = null, $comparison = null)
    {
        if (is_array($unionId)) {
            $useMinMax = false;
            if (isset($unionId['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_UNION_ID, $unionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($unionId['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_UNION_ID, $unionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_UNION_ID, $unionId, $comparison);
    }

    /**
     * Filter the query on the tags column
     *
     * @param     array $tags The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByTags($tags = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PlayerTableMap::COL_TAGS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($tags as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($tags as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($tags as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PlayerTableMap::COL_TAGS, $tags, $comparison);
    }

    /**
     * Filter the query on the tags column
     * @param     mixed $tags The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByTag($tags = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($tags)) {
                $tags = '%| ' . $tags . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $tags = '%| ' . $tags . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PlayerTableMap::COL_TAGS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $tags, $comparison);
            } else {
                $this->addAnd($key, $tags, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PlayerTableMap::COL_TAGS, $tags, $comparison);
    }

    /**
     * Filter the query on the address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE address = 'fooValue'
     * $query->filterByAddress('%fooValue%'); // WHERE address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the die_count column
     *
     * Example usage:
     * <code>
     * $query->filterByDieCount(1234); // WHERE die_count = 1234
     * $query->filterByDieCount(array(12, 34)); // WHERE die_count IN (12, 34)
     * $query->filterByDieCount(array('min' => 12)); // WHERE die_count > 12
     * </code>
     *
     * @param     mixed $dieCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByDieCount($dieCount = null, $comparison = null)
    {
        if (is_array($dieCount)) {
            $useMinMax = false;
            if (isset($dieCount['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_DIE_COUNT, $dieCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dieCount['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_DIE_COUNT, $dieCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_DIE_COUNT, $dieCount, $comparison);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \App\Model\Union object
     *
     * @param \App\Model\Union|ObjectCollection $union The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByUnion($union, $comparison = null)
    {
        if ($union instanceof \App\Model\Union) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_UNION_ID, $union->getId(), $comparison);
        } elseif ($union instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PlayerTableMap::COL_UNION_ID, $union->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUnion() only accepts arguments of type \App\Model\Union or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Union relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinUnion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Union');

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
            $this->addJoinObject($join, 'Union');
        }

        return $this;
    }

    /**
     * Use the Union relation Union object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\UnionQuery A secondary query class using the current class as primary query
     */
    public function useUnionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUnion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Union', '\App\Model\UnionQuery');
    }

    /**
     * Filter the query by a related \App\Model\Timer object
     *
     * @param \App\Model\Timer|ObjectCollection $timer the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByTimer($timer, $comparison = null)
    {
        if ($timer instanceof \App\Model\Timer) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $timer->getPlayerId(), $comparison);
        } elseif ($timer instanceof ObjectCollection) {
            return $this
                ->useTimerQuery()
                ->filterByPrimaryKeys($timer->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTimer() only accepts arguments of type \App\Model\Timer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Timer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinTimer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Timer');

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
            $this->addJoinObject($join, 'Timer');
        }

        return $this;
    }

    /**
     * Use the Timer relation Timer object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\TimerQuery A secondary query class using the current class as primary query
     */
    public function useTimerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTimer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Timer', '\App\Model\TimerQuery');
    }

    /**
     * Filter the query by a related \App\Model\Transaction object
     *
     * @param \App\Model\Transaction|ObjectCollection $transaction the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByTransactionRelatedByPlayerA($transaction, $comparison = null)
    {
        if ($transaction instanceof \App\Model\Transaction) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $transaction->getPlayerA(), $comparison);
        } elseif ($transaction instanceof ObjectCollection) {
            return $this
                ->useTransactionRelatedByPlayerAQuery()
                ->filterByPrimaryKeys($transaction->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTransactionRelatedByPlayerA() only accepts arguments of type \App\Model\Transaction or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TransactionRelatedByPlayerA relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinTransactionRelatedByPlayerA($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TransactionRelatedByPlayerA');

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
            $this->addJoinObject($join, 'TransactionRelatedByPlayerA');
        }

        return $this;
    }

    /**
     * Use the TransactionRelatedByPlayerA relation Transaction object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\TransactionQuery A secondary query class using the current class as primary query
     */
    public function useTransactionRelatedByPlayerAQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTransactionRelatedByPlayerA($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TransactionRelatedByPlayerA', '\App\Model\TransactionQuery');
    }

    /**
     * Filter the query by a related \App\Model\Transaction object
     *
     * @param \App\Model\Transaction|ObjectCollection $transaction the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByTransactionRelatedByPlayerB($transaction, $comparison = null)
    {
        if ($transaction instanceof \App\Model\Transaction) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $transaction->getPlayerB(), $comparison);
        } elseif ($transaction instanceof ObjectCollection) {
            return $this
                ->useTransactionRelatedByPlayerBQuery()
                ->filterByPrimaryKeys($transaction->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTransactionRelatedByPlayerB() only accepts arguments of type \App\Model\Transaction or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TransactionRelatedByPlayerB relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinTransactionRelatedByPlayerB($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TransactionRelatedByPlayerB');

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
            $this->addJoinObject($join, 'TransactionRelatedByPlayerB');
        }

        return $this;
    }

    /**
     * Use the TransactionRelatedByPlayerB relation Transaction object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\TransactionQuery A secondary query class using the current class as primary query
     */
    public function useTransactionRelatedByPlayerBQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTransactionRelatedByPlayerB($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TransactionRelatedByPlayerB', '\App\Model\TransactionQuery');
    }

    /**
     * Filter the query by a related \App\Model\Notification object
     *
     * @param \App\Model\Notification|ObjectCollection $notification the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByNotification($notification, $comparison = null)
    {
        if ($notification instanceof \App\Model\Notification) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $notification->getToPlayer(), $comparison);
        } elseif ($notification instanceof ObjectCollection) {
            return $this
                ->useNotificationQuery()
                ->filterByPrimaryKeys($notification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByNotification() only accepts arguments of type \App\Model\Notification or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Notification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinNotification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Notification');

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
            $this->addJoinObject($join, 'Notification');
        }

        return $this;
    }

    /**
     * Use the Notification relation Notification object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\NotificationQuery A secondary query class using the current class as primary query
     */
    public function useNotificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinNotification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Notification', '\App\Model\NotificationQuery');
    }

    /**
     * Filter the query by a related \App\Model\Account object
     *
     * @param \App\Model\Account|ObjectCollection $account the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof \App\Model\Account) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $account->getPlayerId(), $comparison);
        } elseif ($account instanceof ObjectCollection) {
            return $this
                ->useAccountQuery()
                ->filterByPrimaryKeys($account->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAccount() only accepts arguments of type \App\Model\Account or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Account relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinAccount($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Account');

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
            $this->addJoinObject($join, 'Account');
        }

        return $this;
    }

    /**
     * Use the Account relation Account object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAccount($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Account', '\App\Model\AccountQuery');
    }

    /**
     * Filter the query by a related \App\Model\Session object
     *
     * @param \App\Model\Session|ObjectCollection $session the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterBySession($session, $comparison = null)
    {
        if ($session instanceof \App\Model\Session) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $session->getPlayerId(), $comparison);
        } elseif ($session instanceof ObjectCollection) {
            return $this
                ->useSessionQuery()
                ->filterByPrimaryKeys($session->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySession() only accepts arguments of type \App\Model\Session or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Session relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinSession($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Session');

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
            $this->addJoinObject($join, 'Session');
        }

        return $this;
    }

    /**
     * Use the Session relation Session object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \App\Model\SessionQuery A secondary query class using the current class as primary query
     */
    public function useSessionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSession($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Session', '\App\Model\SessionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayer $player Object to remove from the list of results
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function prune($player = null)
    {
        if ($player) {
            $this->addUsingAlias(PlayerTableMap::COL_ID, $player->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the chrono_player table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerTableMap::clearInstancePool();
            PlayerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PlayerTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PlayerTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PlayerTableMap::COL_CREATED_AT);
    }

} // PlayerQuery
