<?php

namespace App\Model\Map;

use App\Model\Transaction;
use App\Model\TransactionQuery;
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
 * This class defines the structure of the 'chrono_transaction' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class TransactionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'App.Model.Map.TransactionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'chrono';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'chrono_transaction';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\App\\Model\\Transaction';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'App.Model.Transaction';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'chrono_transaction.id';

    /**
     * the column name for the player_a field
     */
    const COL_PLAYER_A = 'chrono_transaction.player_a';

    /**
     * the column name for the player_b field
     */
    const COL_PLAYER_B = 'chrono_transaction.player_b';

    /**
     * the column name for the second field
     */
    const COL_SECOND = 'chrono_transaction.second';

    /**
     * the column name for the executed field
     */
    const COL_EXECUTED = 'chrono_transaction.executed';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'chrono_transaction.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'chrono_transaction.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'PlayerA', 'PlayerB', 'Second', 'Executed', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'playerA', 'playerB', 'second', 'executed', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(TransactionTableMap::COL_ID, TransactionTableMap::COL_PLAYER_A, TransactionTableMap::COL_PLAYER_B, TransactionTableMap::COL_SECOND, TransactionTableMap::COL_EXECUTED, TransactionTableMap::COL_CREATED_AT, TransactionTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'player_a', 'player_b', 'second', 'executed', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'PlayerA' => 1, 'PlayerB' => 2, 'Second' => 3, 'Executed' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'playerA' => 1, 'playerB' => 2, 'second' => 3, 'executed' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(TransactionTableMap::COL_ID => 0, TransactionTableMap::COL_PLAYER_A => 1, TransactionTableMap::COL_PLAYER_B => 2, TransactionTableMap::COL_SECOND => 3, TransactionTableMap::COL_EXECUTED => 4, TransactionTableMap::COL_CREATED_AT => 5, TransactionTableMap::COL_UPDATED_AT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'player_a' => 1, 'player_b' => 2, 'second' => 3, 'executed' => 4, 'created_at' => 5, 'updated_at' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('chrono_transaction');
        $this->setPhpName('Transaction');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\App\\Model\\Transaction');
        $this->setPackage('App.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('player_a', 'PlayerA', 'INTEGER', 'chrono_player', 'id', true, null, null);
        $this->addForeignKey('player_b', 'PlayerB', 'INTEGER', 'chrono_player', 'id', true, null, null);
        $this->addColumn('second', 'Second', 'INTEGER', true, null, null);
        $this->addColumn('executed', 'Executed', 'BOOLEAN', true, 1, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PlayerRelatedByPlayerA', '\\App\\Model\\Player', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':player_a',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PlayerRelatedByPlayerB', '\\App\\Model\\Player', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':player_b',
    1 => ':id',
  ),
), null, null, null, false);
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
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? TransactionTableMap::CLASS_DEFAULT : TransactionTableMap::OM_CLASS;
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
     * @return array           (Transaction object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = TransactionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TransactionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TransactionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TransactionTableMap::OM_CLASS;
            /** @var Transaction $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TransactionTableMap::addInstanceToPool($obj, $key);
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
            $key = TransactionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TransactionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Transaction $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TransactionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(TransactionTableMap::COL_ID);
            $criteria->addSelectColumn(TransactionTableMap::COL_PLAYER_A);
            $criteria->addSelectColumn(TransactionTableMap::COL_PLAYER_B);
            $criteria->addSelectColumn(TransactionTableMap::COL_SECOND);
            $criteria->addSelectColumn(TransactionTableMap::COL_EXECUTED);
            $criteria->addSelectColumn(TransactionTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(TransactionTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.player_a');
            $criteria->addSelectColumn($alias . '.player_b');
            $criteria->addSelectColumn($alias . '.second');
            $criteria->addSelectColumn($alias . '.executed');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(TransactionTableMap::DATABASE_NAME)->getTable(TransactionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(TransactionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(TransactionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new TransactionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Transaction or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Transaction object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(TransactionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \App\Model\Transaction) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TransactionTableMap::DATABASE_NAME);
            $criteria->add(TransactionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = TransactionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TransactionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TransactionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the chrono_transaction table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return TransactionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Transaction or Criteria object.
     *
     * @param mixed               $criteria Criteria or Transaction object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TransactionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Transaction object
        }

        if ($criteria->containsKey(TransactionTableMap::COL_ID) && $criteria->keyContainsValue(TransactionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TransactionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = TransactionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // TransactionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
TransactionTableMap::buildTableMap();
