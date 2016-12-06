<?php

namespace App\Model\Map;

use App\Model\Timer;
use App\Model\TimerQuery;
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
 * This class defines the structure of the 'chrono_timer' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class TimerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'App.Model.Map.TimerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'chrono';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'chrono_timer';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\App\\Model\\Timer';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'App.Model.Timer';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'chrono_timer.id';

    /**
     * the column name for the player_id field
     */
    const COL_PLAYER_ID = 'chrono_timer.player_id';

    /**
     * the column name for the pause field
     */
    const COL_PAUSE = 'chrono_timer.pause';

    /**
     * the column name for the pause_at field
     */
    const COL_PAUSE_AT = 'chrono_timer.pause_at';

    /**
     * the column name for the end_at field
     */
    const COL_END_AT = 'chrono_timer.end_at';

    /**
     * the column name for the class_key field
     */
    const COL_CLASS_KEY = 'chrono_timer.class_key';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'chrono_timer.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'chrono_timer.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** A key representing a particular subclass */
    const CLASSKEY_TIMER = 'timer';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_TIMER = '\\App\\Model\\Timer';

    /** A key representing a particular subclass */
    const CLASSKEY_BOMB = 'bomb';

    /** A class that can be returned by this tableMap. */
    const CLASSNAME_BOMB = '\\App\\Model\\Bomb';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'PlayerId', 'Pause', 'PauseAt', 'EndAt', 'ClassKey', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'playerId', 'pause', 'pauseAt', 'endAt', 'classKey', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(TimerTableMap::COL_ID, TimerTableMap::COL_PLAYER_ID, TimerTableMap::COL_PAUSE, TimerTableMap::COL_PAUSE_AT, TimerTableMap::COL_END_AT, TimerTableMap::COL_CLASS_KEY, TimerTableMap::COL_CREATED_AT, TimerTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'player_id', 'pause', 'pause_at', 'end_at', 'class_key', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'PlayerId' => 1, 'Pause' => 2, 'PauseAt' => 3, 'EndAt' => 4, 'ClassKey' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'playerId' => 1, 'pause' => 2, 'pauseAt' => 3, 'endAt' => 4, 'classKey' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(TimerTableMap::COL_ID => 0, TimerTableMap::COL_PLAYER_ID => 1, TimerTableMap::COL_PAUSE => 2, TimerTableMap::COL_PAUSE_AT => 3, TimerTableMap::COL_END_AT => 4, TimerTableMap::COL_CLASS_KEY => 5, TimerTableMap::COL_CREATED_AT => 6, TimerTableMap::COL_UPDATED_AT => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'player_id' => 1, 'pause' => 2, 'pause_at' => 3, 'end_at' => 4, 'class_key' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('chrono_timer');
        $this->setPhpName('Timer');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\App\\Model\\Timer');
        $this->setPackage('App.Model');
        $this->setUseIdGenerator(true);
        $this->setSingleTableInheritance(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('player_id', 'PlayerId', 'INTEGER', 'chrono_player', 'id', true, null, null);
        $this->addColumn('pause', 'Pause', 'BOOLEAN', true, 1, false);
        $this->addColumn('pause_at', 'PauseAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('end_at', 'EndAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('class_key', 'ClassKey', 'VARCHAR', false, 20, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Player', '\\App\\Model\\Player', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':player_id',
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
     * The returned Class will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param array   $row ConnectionInterface result row.
     * @param int     $colnum Column to examine for OM class information (first is 0).
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     *
     * @return string The OM class
     */
    public static function getOMClass($row, $colnum, $withPrefix = true)
    {
        try {

            $omClass = null;
            $classKey = $row[$colnum + 5];

            switch ($classKey) {

                case TimerTableMap::CLASSKEY_TIMER:
                    $omClass = TimerTableMap::CLASSNAME_TIMER;
                    break;

                case TimerTableMap::CLASSKEY_BOMB:
                    $omClass = TimerTableMap::CLASSNAME_BOMB;
                    break;

                default:
                    $omClass = TimerTableMap::CLASS_DEFAULT;

            } // switch
            if (!$withPrefix) {
                $omClass = preg_replace('#\.#', '\\', $omClass);
            }

        } catch (\Exception $e) {
            throw new PropelException('Unable to get OM class.', $e);
        }

        return $omClass;
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
     * @return array           (Timer object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = TimerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TimerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TimerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = static::getOMClass($row, $offset, false);
            /** @var Timer $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TimerTableMap::addInstanceToPool($obj, $key);
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

        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = TimerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TimerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                // class must be set each time from the record row
                $cls = static::getOMClass($row, 0);
                $cls = preg_replace('#\.#', '\\', $cls);
                /** @var Timer $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TimerTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(TimerTableMap::COL_ID);
            $criteria->addSelectColumn(TimerTableMap::COL_PLAYER_ID);
            $criteria->addSelectColumn(TimerTableMap::COL_PAUSE);
            $criteria->addSelectColumn(TimerTableMap::COL_PAUSE_AT);
            $criteria->addSelectColumn(TimerTableMap::COL_END_AT);
            $criteria->addSelectColumn(TimerTableMap::COL_CLASS_KEY);
            $criteria->addSelectColumn(TimerTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(TimerTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.player_id');
            $criteria->addSelectColumn($alias . '.pause');
            $criteria->addSelectColumn($alias . '.pause_at');
            $criteria->addSelectColumn($alias . '.end_at');
            $criteria->addSelectColumn($alias . '.class_key');
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
        return Propel::getServiceContainer()->getDatabaseMap(TimerTableMap::DATABASE_NAME)->getTable(TimerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(TimerTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(TimerTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new TimerTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Timer or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Timer object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(TimerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \App\Model\Timer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TimerTableMap::DATABASE_NAME);
            $criteria->add(TimerTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = TimerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TimerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TimerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the chrono_timer table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return TimerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Timer or Criteria object.
     *
     * @param mixed               $criteria Criteria or Timer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TimerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Timer object
        }

        if ($criteria->containsKey(TimerTableMap::COL_ID) && $criteria->keyContainsValue(TimerTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TimerTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = TimerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // TimerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
TimerTableMap::buildTableMap();
