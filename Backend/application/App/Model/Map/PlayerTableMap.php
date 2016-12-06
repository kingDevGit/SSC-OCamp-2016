<?php

namespace App\Model\Map;

use App\Model\Player;
use App\Model\PlayerQuery;
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
 * This class defines the structure of the 'chrono_player' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PlayerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'App.Model.Map.PlayerTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'chrono';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'chrono_player';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\App\\Model\\Player';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'App.Model.Player';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'chrono_player.id';

    /**
     * the column name for the nickname field
     */
    const COL_NICKNAME = 'chrono_player.nickname';

    /**
     * the column name for the gender field
     */
    const COL_GENDER = 'chrono_player.gender';

    /**
     * the column name for the union_id field
     */
    const COL_UNION_ID = 'chrono_player.union_id';

    /**
     * the column name for the tags field
     */
    const COL_TAGS = 'chrono_player.tags';

    /**
     * the column name for the address field
     */
    const COL_ADDRESS = 'chrono_player.address';

    /**
     * the column name for the die_count field
     */
    const COL_DIE_COUNT = 'chrono_player.die_count';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'chrono_player.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'chrono_player.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the gender field */
    const COL_GENDER_MALE = 'male';
    const COL_GENDER_FEMALE = 'female';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Nickname', 'Gender', 'UnionId', 'Tags', 'Address', 'DieCount', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'nickname', 'gender', 'unionId', 'tags', 'address', 'dieCount', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PlayerTableMap::COL_ID, PlayerTableMap::COL_NICKNAME, PlayerTableMap::COL_GENDER, PlayerTableMap::COL_UNION_ID, PlayerTableMap::COL_TAGS, PlayerTableMap::COL_ADDRESS, PlayerTableMap::COL_DIE_COUNT, PlayerTableMap::COL_CREATED_AT, PlayerTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'nickname', 'gender', 'union_id', 'tags', 'address', 'die_count', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Nickname' => 1, 'Gender' => 2, 'UnionId' => 3, 'Tags' => 4, 'Address' => 5, 'DieCount' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'nickname' => 1, 'gender' => 2, 'unionId' => 3, 'tags' => 4, 'address' => 5, 'dieCount' => 6, 'createdAt' => 7, 'updatedAt' => 8, ),
        self::TYPE_COLNAME       => array(PlayerTableMap::COL_ID => 0, PlayerTableMap::COL_NICKNAME => 1, PlayerTableMap::COL_GENDER => 2, PlayerTableMap::COL_UNION_ID => 3, PlayerTableMap::COL_TAGS => 4, PlayerTableMap::COL_ADDRESS => 5, PlayerTableMap::COL_DIE_COUNT => 6, PlayerTableMap::COL_CREATED_AT => 7, PlayerTableMap::COL_UPDATED_AT => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'nickname' => 1, 'gender' => 2, 'union_id' => 3, 'tags' => 4, 'address' => 5, 'die_count' => 6, 'created_at' => 7, 'updated_at' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                PlayerTableMap::COL_GENDER => array(
                            self::COL_GENDER_MALE,
            self::COL_GENDER_FEMALE,
        ),
    );

    /**
     * Gets the list of values for all ENUM and SET columns
     * @return array
     */
    public static function getValueSets()
    {
      return static::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM or SET column
     * @param string $colname
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        return $valueSets[$colname];
    }

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
        $this->setName('chrono_player');
        $this->setPhpName('Player');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\App\\Model\\Player');
        $this->setPackage('App.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('nickname', 'Nickname', 'VARCHAR', true, 255, null);
        $this->addColumn('gender', 'Gender', 'ENUM', true, null, null);
        $this->getColumn('gender')->setValueSet(array (
  0 => 'male',
  1 => 'female',
));
        $this->addForeignKey('union_id', 'UnionId', 'INTEGER', 'chrono_union', 'id', false, null, null);
        $this->addColumn('tags', 'Tags', 'ARRAY', false, null, null);
        $this->addColumn('address', 'Address', 'VARCHAR', true, 10, null);
        $this->addColumn('die_count', 'DieCount', 'INTEGER', true, null, 0);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Union', '\\App\\Model\\Union', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':union_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Timer', '\\App\\Model\\Timer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, 'Timers', false);
        $this->addRelation('TransactionRelatedByPlayerA', '\\App\\Model\\Transaction', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_a',
    1 => ':id',
  ),
), null, null, 'TransactionsRelatedByPlayerA', false);
        $this->addRelation('TransactionRelatedByPlayerB', '\\App\\Model\\Transaction', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_b',
    1 => ':id',
  ),
), null, null, 'TransactionsRelatedByPlayerB', false);
        $this->addRelation('Notification', '\\App\\Model\\Notification', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':to_player',
    1 => ':id',
  ),
), null, null, 'Notifications', false);
        $this->addRelation('Account', '\\App\\Model\\Account', RelationMap::ONE_TO_ONE, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Session', '\\App\\Model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':player_id',
    1 => ':id',
  ),
), null, null, 'Sessions', false);
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
            'validate' => array('nickname_required' => array ('column' => 'nickname','validator' => 'NotBlank',), 'address_required' => array ('column' => 'address','validator' => 'NotBlank',), ),
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
        return $withPrefix ? PlayerTableMap::CLASS_DEFAULT : PlayerTableMap::OM_CLASS;
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
     * @return array           (Player object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PlayerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PlayerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PlayerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PlayerTableMap::OM_CLASS;
            /** @var Player $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PlayerTableMap::addInstanceToPool($obj, $key);
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
            $key = PlayerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PlayerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Player $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PlayerTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PlayerTableMap::COL_ID);
            $criteria->addSelectColumn(PlayerTableMap::COL_NICKNAME);
            $criteria->addSelectColumn(PlayerTableMap::COL_GENDER);
            $criteria->addSelectColumn(PlayerTableMap::COL_UNION_ID);
            $criteria->addSelectColumn(PlayerTableMap::COL_TAGS);
            $criteria->addSelectColumn(PlayerTableMap::COL_ADDRESS);
            $criteria->addSelectColumn(PlayerTableMap::COL_DIE_COUNT);
            $criteria->addSelectColumn(PlayerTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(PlayerTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.nickname');
            $criteria->addSelectColumn($alias . '.gender');
            $criteria->addSelectColumn($alias . '.union_id');
            $criteria->addSelectColumn($alias . '.tags');
            $criteria->addSelectColumn($alias . '.address');
            $criteria->addSelectColumn($alias . '.die_count');
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
        return Propel::getServiceContainer()->getDatabaseMap(PlayerTableMap::DATABASE_NAME)->getTable(PlayerTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PlayerTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PlayerTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PlayerTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Player or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Player object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \App\Model\Player) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PlayerTableMap::DATABASE_NAME);
            $criteria->add(PlayerTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PlayerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PlayerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PlayerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the chrono_player table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PlayerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Player or Criteria object.
     *
     * @param mixed               $criteria Criteria or Player object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Player object
        }

        if ($criteria->containsKey(PlayerTableMap::COL_ID) && $criteria->keyContainsValue(PlayerTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PlayerTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PlayerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PlayerTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PlayerTableMap::buildTableMap();
