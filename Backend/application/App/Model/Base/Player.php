<?php

namespace App\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use App\Model\Account as ChildAccount;
use App\Model\AccountQuery as ChildAccountQuery;
use App\Model\Notification as ChildNotification;
use App\Model\NotificationQuery as ChildNotificationQuery;
use App\Model\Player as ChildPlayer;
use App\Model\PlayerQuery as ChildPlayerQuery;
use App\Model\Session as ChildSession;
use App\Model\SessionQuery as ChildSessionQuery;
use App\Model\Timer as ChildTimer;
use App\Model\TimerQuery as ChildTimerQuery;
use App\Model\Transaction as ChildTransaction;
use App\Model\TransactionQuery as ChildTransactionQuery;
use App\Model\Union as ChildUnion;
use App\Model\UnionQuery as ChildUnionQuery;
use App\Model\Map\NotificationTableMap;
use App\Model\Map\PlayerTableMap;
use App\Model\Map\SessionTableMap;
use App\Model\Map\TimerTableMap;
use App\Model\Map\TransactionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Base class that represents a row from the 'chrono_player' table.
 *
 *
 *
 * @package    propel.generator.App.Model.Base
 */
abstract class Player implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\App\\Model\\Map\\PlayerTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the nickname field.
     *
     * @var        string
     */
    protected $nickname;

    /**
     * The value for the gender field.
     *
     * @var        int
     */
    protected $gender;

    /**
     * The value for the union_id field.
     *
     * @var        int
     */
    protected $union_id;

    /**
     * The value for the tags field.
     *
     * @var        array
     */
    protected $tags;

    /**
     * The unserialized $tags value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $tags_unserialized;

    /**
     * The value for the address field.
     *
     * @var        string
     */
    protected $address;

    /**
     * The value for the die_count field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $die_count;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildUnion
     */
    protected $aUnion;

    /**
     * @var        ObjectCollection|ChildTimer[] Collection to store aggregation of ChildTimer objects.
     */
    protected $collTimers;
    protected $collTimersPartial;

    /**
     * @var        ObjectCollection|ChildTransaction[] Collection to store aggregation of ChildTransaction objects.
     */
    protected $collTransactionsRelatedByPlayerA;
    protected $collTransactionsRelatedByPlayerAPartial;

    /**
     * @var        ObjectCollection|ChildTransaction[] Collection to store aggregation of ChildTransaction objects.
     */
    protected $collTransactionsRelatedByPlayerB;
    protected $collTransactionsRelatedByPlayerBPartial;

    /**
     * @var        ObjectCollection|ChildNotification[] Collection to store aggregation of ChildNotification objects.
     */
    protected $collNotifications;
    protected $collNotificationsPartial;

    /**
     * @var        ChildAccount one-to-one related ChildAccount object
     */
    protected $singleAccount;

    /**
     * @var        ObjectCollection|ChildSession[] Collection to store aggregation of ChildSession objects.
     */
    protected $collSessions;
    protected $collSessionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // validate behavior

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * ConstraintViolationList object
     *
     * @see     http://api.symfony.com/2.0/Symfony/Component/Validator/ConstraintViolationList.html
     * @var     ConstraintViolationList
     */
    protected $validationFailures;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTimer[]
     */
    protected $timersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTransaction[]
     */
    protected $transactionsRelatedByPlayerAScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTransaction[]
     */
    protected $transactionsRelatedByPlayerBScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildNotification[]
     */
    protected $notificationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSession[]
     */
    protected $sessionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->die_count = 0;
    }

    /**
     * Initializes internal state of App\Model\Base\Player object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Player</code> instance.  If
     * <code>obj</code> is an instance of <code>Player</code>, delegates to
     * <code>equals(Player)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Player The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [nickname] column value.
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Get the [gender] column value.
     *
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getGender()
    {
        if (null === $this->gender) {
            return null;
        }
        $valueSet = PlayerTableMap::getValueSet(PlayerTableMap::COL_GENDER);
        if (!isset($valueSet[$this->gender])) {
            throw new PropelException('Unknown stored enum key: ' . $this->gender);
        }

        return $valueSet[$this->gender];
    }

    /**
     * Get the [union_id] column value.
     *
     * @return int
     */
    public function getUnionId()
    {
        return $this->union_id;
    }

    /**
     * Get the [tags] column value.
     *
     * @return array
     */
    public function getTags()
    {
        if (null === $this->tags_unserialized) {
            $this->tags_unserialized = array();
        }
        if (!$this->tags_unserialized && null !== $this->tags) {
            $tags_unserialized = substr($this->tags, 2, -2);
            $this->tags_unserialized = $tags_unserialized ? explode(' | ', $tags_unserialized) : array();
        }

        return $this->tags_unserialized;
    }

    /**
     * Test the presence of a value in the [tags] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasTag($value)
    {
        return in_array($value, $this->getTags());
    } // hasTag()

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the [die_count] column value.
     *
     * @return int
     */
    public function getDieCount()
    {
        return $this->die_count;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PlayerTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [nickname] column.
     *
     * @param string $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setNickname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nickname !== $v) {
            $this->nickname = $v;
            $this->modifiedColumns[PlayerTableMap::COL_NICKNAME] = true;
        }

        return $this;
    } // setNickname()

    /**
     * Set the value of [gender] column.
     *
     * @param  string $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $valueSet = PlayerTableMap::getValueSet(PlayerTableMap::COL_GENDER);
            if (!in_array($v, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $v));
            }
            $v = array_search($v, $valueSet);
        }

        if ($this->gender !== $v) {
            $this->gender = $v;
            $this->modifiedColumns[PlayerTableMap::COL_GENDER] = true;
        }

        return $this;
    } // setGender()

    /**
     * Set the value of [union_id] column.
     *
     * @param int $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setUnionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->union_id !== $v) {
            $this->union_id = $v;
            $this->modifiedColumns[PlayerTableMap::COL_UNION_ID] = true;
        }

        if ($this->aUnion !== null && $this->aUnion->getId() !== $v) {
            $this->aUnion = null;
        }

        return $this;
    } // setUnionId()

    /**
     * Set the value of [tags] column.
     *
     * @param array $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setTags($v)
    {
        if ($this->tags_unserialized !== $v) {
            $this->tags_unserialized = $v;
            $this->tags = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PlayerTableMap::COL_TAGS] = true;
        }

        return $this;
    } // setTags()

    /**
     * Adds a value to the [tags] array column value.
     * @param  mixed $value
     *
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function addTag($value)
    {
        $currentArray = $this->getTags();
        $currentArray []= $value;
        $this->setTags($currentArray);

        return $this;
    } // addTag()

    /**
     * Removes a value from the [tags] array column value.
     * @param  mixed $value
     *
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function removeTag($value)
    {
        $targetArray = array();
        foreach ($this->getTags() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setTags($targetArray);

        return $this;
    } // removeTag()

    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->address !== $v) {
            $this->address = $v;
            $this->modifiedColumns[PlayerTableMap::COL_ADDRESS] = true;
        }

        return $this;
    } // setAddress()

    /**
     * Set the value of [die_count] column.
     *
     * @param int $v new value
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setDieCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->die_count !== $v) {
            $this->die_count = $v;
            $this->modifiedColumns[PlayerTableMap::COL_DIE_COUNT] = true;
        }

        return $this;
    } // setDieCount()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PlayerTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->die_count !== 0) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PlayerTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PlayerTableMap::translateFieldName('Nickname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nickname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PlayerTableMap::translateFieldName('Gender', TableMap::TYPE_PHPNAME, $indexType)];
            $this->gender = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PlayerTableMap::translateFieldName('UnionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->union_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PlayerTableMap::translateFieldName('Tags', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tags = $col;
            $this->tags_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PlayerTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PlayerTableMap::translateFieldName('DieCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->die_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PlayerTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PlayerTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = PlayerTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\App\\Model\\Player'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aUnion !== null && $this->union_id !== $this->aUnion->getId()) {
            $this->aUnion = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPlayerQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUnion = null;
            $this->collTimers = null;

            $this->collTransactionsRelatedByPlayerA = null;

            $this->collTransactionsRelatedByPlayerB = null;

            $this->collNotifications = null;

            $this->singleAccount = null;

            $this->collSessions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Player::setDeleted()
     * @see Player::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPlayerQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(PlayerTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PlayerTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PlayerTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PlayerTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUnion !== null) {
                if ($this->aUnion->isModified() || $this->aUnion->isNew()) {
                    $affectedRows += $this->aUnion->save($con);
                }
                $this->setUnion($this->aUnion);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->timersScheduledForDeletion !== null) {
                if (!$this->timersScheduledForDeletion->isEmpty()) {
                    \App\Model\TimerQuery::create()
                        ->filterByPrimaryKeys($this->timersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->timersScheduledForDeletion = null;
                }
            }

            if ($this->collTimers !== null) {
                foreach ($this->collTimers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->transactionsRelatedByPlayerAScheduledForDeletion !== null) {
                if (!$this->transactionsRelatedByPlayerAScheduledForDeletion->isEmpty()) {
                    \App\Model\TransactionQuery::create()
                        ->filterByPrimaryKeys($this->transactionsRelatedByPlayerAScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->transactionsRelatedByPlayerAScheduledForDeletion = null;
                }
            }

            if ($this->collTransactionsRelatedByPlayerA !== null) {
                foreach ($this->collTransactionsRelatedByPlayerA as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->transactionsRelatedByPlayerBScheduledForDeletion !== null) {
                if (!$this->transactionsRelatedByPlayerBScheduledForDeletion->isEmpty()) {
                    \App\Model\TransactionQuery::create()
                        ->filterByPrimaryKeys($this->transactionsRelatedByPlayerBScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->transactionsRelatedByPlayerBScheduledForDeletion = null;
                }
            }

            if ($this->collTransactionsRelatedByPlayerB !== null) {
                foreach ($this->collTransactionsRelatedByPlayerB as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->notificationsScheduledForDeletion !== null) {
                if (!$this->notificationsScheduledForDeletion->isEmpty()) {
                    \App\Model\NotificationQuery::create()
                        ->filterByPrimaryKeys($this->notificationsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->notificationsScheduledForDeletion = null;
                }
            }

            if ($this->collNotifications !== null) {
                foreach ($this->collNotifications as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->singleAccount !== null) {
                if (!$this->singleAccount->isDeleted() && ($this->singleAccount->isNew() || $this->singleAccount->isModified())) {
                    $affectedRows += $this->singleAccount->save($con);
                }
            }

            if ($this->sessionsScheduledForDeletion !== null) {
                if (!$this->sessionsScheduledForDeletion->isEmpty()) {
                    \App\Model\SessionQuery::create()
                        ->filterByPrimaryKeys($this->sessionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->sessionsScheduledForDeletion = null;
                }
            }

            if ($this->collSessions !== null) {
                foreach ($this->collSessions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[PlayerTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PlayerTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PlayerTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_NICKNAME)) {
            $modifiedColumns[':p' . $index++]  = 'nickname';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_GENDER)) {
            $modifiedColumns[':p' . $index++]  = 'gender';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_UNION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'union_id';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_TAGS)) {
            $modifiedColumns[':p' . $index++]  = 'tags';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'address';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_DIE_COUNT)) {
            $modifiedColumns[':p' . $index++]  = 'die_count';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(PlayerTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO chrono_player (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'nickname':
                        $stmt->bindValue($identifier, $this->nickname, PDO::PARAM_STR);
                        break;
                    case 'gender':
                        $stmt->bindValue($identifier, $this->gender, PDO::PARAM_INT);
                        break;
                    case 'union_id':
                        $stmt->bindValue($identifier, $this->union_id, PDO::PARAM_INT);
                        break;
                    case 'tags':
                        $stmt->bindValue($identifier, $this->tags, PDO::PARAM_STR);
                        break;
                    case 'address':
                        $stmt->bindValue($identifier, $this->address, PDO::PARAM_STR);
                        break;
                    case 'die_count':
                        $stmt->bindValue($identifier, $this->die_count, PDO::PARAM_INT);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getNickname();
                break;
            case 2:
                return $this->getGender();
                break;
            case 3:
                return $this->getUnionId();
                break;
            case 4:
                return $this->getTags();
                break;
            case 5:
                return $this->getAddress();
                break;
            case 6:
                return $this->getDieCount();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Player'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Player'][$this->hashCode()] = true;
        $keys = PlayerTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNickname(),
            $keys[2] => $this->getGender(),
            $keys[3] => $this->getUnionId(),
            $keys[4] => $this->getTags(),
            $keys[5] => $this->getAddress(),
            $keys[6] => $this->getDieCount(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        if ($result[$keys[8]] instanceof \DateTime) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUnion) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'union';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_union';
                        break;
                    default:
                        $key = 'Union';
                }

                $result[$key] = $this->aUnion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collTimers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'timers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_timers';
                        break;
                    default:
                        $key = 'Timers';
                }

                $result[$key] = $this->collTimers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTransactionsRelatedByPlayerA) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'transactions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_transactions';
                        break;
                    default:
                        $key = 'Transactions';
                }

                $result[$key] = $this->collTransactionsRelatedByPlayerA->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTransactionsRelatedByPlayerB) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'transactions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_transactions';
                        break;
                    default:
                        $key = 'Transactions';
                }

                $result[$key] = $this->collTransactionsRelatedByPlayerB->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collNotifications) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'notifications';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_notifications';
                        break;
                    default:
                        $key = 'Notifications';
                }

                $result[$key] = $this->collNotifications->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->singleAccount) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'account';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_account';
                        break;
                    default:
                        $key = 'Account';
                }

                $result[$key] = $this->singleAccount->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collSessions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'sessions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chrono_sessions';
                        break;
                    default:
                        $key = 'Sessions';
                }

                $result[$key] = $this->collSessions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\App\Model\Player
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PlayerTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\App\Model\Player
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setNickname($value);
                break;
            case 2:
                $valueSet = PlayerTableMap::getValueSet(PlayerTableMap::COL_GENDER);
                if (isset($valueSet[$value])) {
                    $value = $valueSet[$value];
                }
                $this->setGender($value);
                break;
            case 3:
                $this->setUnionId($value);
                break;
            case 4:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setTags($value);
                break;
            case 5:
                $this->setAddress($value);
                break;
            case 6:
                $this->setDieCount($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = PlayerTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setNickname($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setGender($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUnionId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setTags($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAddress($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setDieCount($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCreatedAt($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setUpdatedAt($arr[$keys[8]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\App\Model\Player The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PlayerTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PlayerTableMap::COL_ID)) {
            $criteria->add(PlayerTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_NICKNAME)) {
            $criteria->add(PlayerTableMap::COL_NICKNAME, $this->nickname);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_GENDER)) {
            $criteria->add(PlayerTableMap::COL_GENDER, $this->gender);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_UNION_ID)) {
            $criteria->add(PlayerTableMap::COL_UNION_ID, $this->union_id);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_TAGS)) {
            $criteria->add(PlayerTableMap::COL_TAGS, $this->tags);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_ADDRESS)) {
            $criteria->add(PlayerTableMap::COL_ADDRESS, $this->address);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_DIE_COUNT)) {
            $criteria->add(PlayerTableMap::COL_DIE_COUNT, $this->die_count);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_CREATED_AT)) {
            $criteria->add(PlayerTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(PlayerTableMap::COL_UPDATED_AT)) {
            $criteria->add(PlayerTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildPlayerQuery::create();
        $criteria->add(PlayerTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \App\Model\Player (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNickname($this->getNickname());
        $copyObj->setGender($this->getGender());
        $copyObj->setUnionId($this->getUnionId());
        $copyObj->setTags($this->getTags());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setDieCount($this->getDieCount());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getTimers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTimer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTransactionsRelatedByPlayerA() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTransactionRelatedByPlayerA($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTransactionsRelatedByPlayerB() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTransactionRelatedByPlayerB($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getNotifications() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addNotification($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getAccount();
            if ($relObj) {
                $copyObj->setAccount($relObj->copy($deepCopy));
            }

            foreach ($this->getSessions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSession($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \App\Model\Player Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildUnion object.
     *
     * @param  ChildUnion $v
     * @return $this|\App\Model\Player The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUnion(ChildUnion $v = null)
    {
        if ($v === null) {
            $this->setUnionId(NULL);
        } else {
            $this->setUnionId($v->getId());
        }

        $this->aUnion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUnion object, it will not be re-added.
        if ($v !== null) {
            $v->addPlayer($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUnion object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUnion The associated ChildUnion object.
     * @throws PropelException
     */
    public function getUnion(ConnectionInterface $con = null)
    {
        if ($this->aUnion === null && ($this->union_id !== null)) {
            $this->aUnion = ChildUnionQuery::create()->findPk($this->union_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUnion->addPlayers($this);
             */
        }

        return $this->aUnion;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Timer' == $relationName) {
            return $this->initTimers();
        }
        if ('TransactionRelatedByPlayerA' == $relationName) {
            return $this->initTransactionsRelatedByPlayerA();
        }
        if ('TransactionRelatedByPlayerB' == $relationName) {
            return $this->initTransactionsRelatedByPlayerB();
        }
        if ('Notification' == $relationName) {
            return $this->initNotifications();
        }
        if ('Session' == $relationName) {
            return $this->initSessions();
        }
    }

    /**
     * Clears out the collTimers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTimers()
     */
    public function clearTimers()
    {
        $this->collTimers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTimers collection loaded partially.
     */
    public function resetPartialTimers($v = true)
    {
        $this->collTimersPartial = $v;
    }

    /**
     * Initializes the collTimers collection.
     *
     * By default this just sets the collTimers collection to an empty array (like clearcollTimers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTimers($overrideExisting = true)
    {
        if (null !== $this->collTimers && !$overrideExisting) {
            return;
        }

        $collectionClassName = TimerTableMap::getTableMap()->getCollectionClassName();

        $this->collTimers = new $collectionClassName;
        $this->collTimers->setModel('\App\Model\Timer');
    }

    /**
     * Gets an array of ChildTimer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTimer[] List of ChildTimer objects
     * @throws PropelException
     */
    public function getTimers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTimersPartial && !$this->isNew();
        if (null === $this->collTimers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTimers) {
                // return empty collection
                $this->initTimers();
            } else {
                $collTimers = ChildTimerQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTimersPartial && count($collTimers)) {
                        $this->initTimers(false);

                        foreach ($collTimers as $obj) {
                            if (false == $this->collTimers->contains($obj)) {
                                $this->collTimers->append($obj);
                            }
                        }

                        $this->collTimersPartial = true;
                    }

                    return $collTimers;
                }

                if ($partial && $this->collTimers) {
                    foreach ($this->collTimers as $obj) {
                        if ($obj->isNew()) {
                            $collTimers[] = $obj;
                        }
                    }
                }

                $this->collTimers = $collTimers;
                $this->collTimersPartial = false;
            }
        }

        return $this->collTimers;
    }

    /**
     * Sets a collection of ChildTimer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $timers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setTimers(Collection $timers, ConnectionInterface $con = null)
    {
        /** @var ChildTimer[] $timersToDelete */
        $timersToDelete = $this->getTimers(new Criteria(), $con)->diff($timers);


        $this->timersScheduledForDeletion = $timersToDelete;

        foreach ($timersToDelete as $timerRemoved) {
            $timerRemoved->setPlayer(null);
        }

        $this->collTimers = null;
        foreach ($timers as $timer) {
            $this->addTimer($timer);
        }

        $this->collTimers = $timers;
        $this->collTimersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Timer objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Timer objects.
     * @throws PropelException
     */
    public function countTimers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTimersPartial && !$this->isNew();
        if (null === $this->collTimers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTimers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTimers());
            }

            $query = ChildTimerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collTimers);
    }

    /**
     * Method called to associate a ChildTimer object to this object
     * through the ChildTimer foreign key attribute.
     *
     * @param  ChildTimer $l ChildTimer
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function addTimer(ChildTimer $l)
    {
        if ($this->collTimers === null) {
            $this->initTimers();
            $this->collTimersPartial = true;
        }

        if (!$this->collTimers->contains($l)) {
            $this->doAddTimer($l);

            if ($this->timersScheduledForDeletion and $this->timersScheduledForDeletion->contains($l)) {
                $this->timersScheduledForDeletion->remove($this->timersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTimer $timer The ChildTimer object to add.
     */
    protected function doAddTimer(ChildTimer $timer)
    {
        $this->collTimers[]= $timer;
        $timer->setPlayer($this);
    }

    /**
     * @param  ChildTimer $timer The ChildTimer object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removeTimer(ChildTimer $timer)
    {
        if ($this->getTimers()->contains($timer)) {
            $pos = $this->collTimers->search($timer);
            $this->collTimers->remove($pos);
            if (null === $this->timersScheduledForDeletion) {
                $this->timersScheduledForDeletion = clone $this->collTimers;
                $this->timersScheduledForDeletion->clear();
            }
            $this->timersScheduledForDeletion[]= clone $timer;
            $timer->setPlayer(null);
        }

        return $this;
    }

    /**
     * Clears out the collTransactionsRelatedByPlayerA collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTransactionsRelatedByPlayerA()
     */
    public function clearTransactionsRelatedByPlayerA()
    {
        $this->collTransactionsRelatedByPlayerA = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTransactionsRelatedByPlayerA collection loaded partially.
     */
    public function resetPartialTransactionsRelatedByPlayerA($v = true)
    {
        $this->collTransactionsRelatedByPlayerAPartial = $v;
    }

    /**
     * Initializes the collTransactionsRelatedByPlayerA collection.
     *
     * By default this just sets the collTransactionsRelatedByPlayerA collection to an empty array (like clearcollTransactionsRelatedByPlayerA());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTransactionsRelatedByPlayerA($overrideExisting = true)
    {
        if (null !== $this->collTransactionsRelatedByPlayerA && !$overrideExisting) {
            return;
        }

        $collectionClassName = TransactionTableMap::getTableMap()->getCollectionClassName();

        $this->collTransactionsRelatedByPlayerA = new $collectionClassName;
        $this->collTransactionsRelatedByPlayerA->setModel('\App\Model\Transaction');
    }

    /**
     * Gets an array of ChildTransaction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTransaction[] List of ChildTransaction objects
     * @throws PropelException
     */
    public function getTransactionsRelatedByPlayerA(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTransactionsRelatedByPlayerAPartial && !$this->isNew();
        if (null === $this->collTransactionsRelatedByPlayerA || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTransactionsRelatedByPlayerA) {
                // return empty collection
                $this->initTransactionsRelatedByPlayerA();
            } else {
                $collTransactionsRelatedByPlayerA = ChildTransactionQuery::create(null, $criteria)
                    ->filterByPlayerRelatedByPlayerA($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTransactionsRelatedByPlayerAPartial && count($collTransactionsRelatedByPlayerA)) {
                        $this->initTransactionsRelatedByPlayerA(false);

                        foreach ($collTransactionsRelatedByPlayerA as $obj) {
                            if (false == $this->collTransactionsRelatedByPlayerA->contains($obj)) {
                                $this->collTransactionsRelatedByPlayerA->append($obj);
                            }
                        }

                        $this->collTransactionsRelatedByPlayerAPartial = true;
                    }

                    return $collTransactionsRelatedByPlayerA;
                }

                if ($partial && $this->collTransactionsRelatedByPlayerA) {
                    foreach ($this->collTransactionsRelatedByPlayerA as $obj) {
                        if ($obj->isNew()) {
                            $collTransactionsRelatedByPlayerA[] = $obj;
                        }
                    }
                }

                $this->collTransactionsRelatedByPlayerA = $collTransactionsRelatedByPlayerA;
                $this->collTransactionsRelatedByPlayerAPartial = false;
            }
        }

        return $this->collTransactionsRelatedByPlayerA;
    }

    /**
     * Sets a collection of ChildTransaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $transactionsRelatedByPlayerA A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setTransactionsRelatedByPlayerA(Collection $transactionsRelatedByPlayerA, ConnectionInterface $con = null)
    {
        /** @var ChildTransaction[] $transactionsRelatedByPlayerAToDelete */
        $transactionsRelatedByPlayerAToDelete = $this->getTransactionsRelatedByPlayerA(new Criteria(), $con)->diff($transactionsRelatedByPlayerA);


        $this->transactionsRelatedByPlayerAScheduledForDeletion = $transactionsRelatedByPlayerAToDelete;

        foreach ($transactionsRelatedByPlayerAToDelete as $transactionRelatedByPlayerARemoved) {
            $transactionRelatedByPlayerARemoved->setPlayerRelatedByPlayerA(null);
        }

        $this->collTransactionsRelatedByPlayerA = null;
        foreach ($transactionsRelatedByPlayerA as $transactionRelatedByPlayerA) {
            $this->addTransactionRelatedByPlayerA($transactionRelatedByPlayerA);
        }

        $this->collTransactionsRelatedByPlayerA = $transactionsRelatedByPlayerA;
        $this->collTransactionsRelatedByPlayerAPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Transaction objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Transaction objects.
     * @throws PropelException
     */
    public function countTransactionsRelatedByPlayerA(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTransactionsRelatedByPlayerAPartial && !$this->isNew();
        if (null === $this->collTransactionsRelatedByPlayerA || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTransactionsRelatedByPlayerA) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTransactionsRelatedByPlayerA());
            }

            $query = ChildTransactionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerRelatedByPlayerA($this)
                ->count($con);
        }

        return count($this->collTransactionsRelatedByPlayerA);
    }

    /**
     * Method called to associate a ChildTransaction object to this object
     * through the ChildTransaction foreign key attribute.
     *
     * @param  ChildTransaction $l ChildTransaction
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function addTransactionRelatedByPlayerA(ChildTransaction $l)
    {
        if ($this->collTransactionsRelatedByPlayerA === null) {
            $this->initTransactionsRelatedByPlayerA();
            $this->collTransactionsRelatedByPlayerAPartial = true;
        }

        if (!$this->collTransactionsRelatedByPlayerA->contains($l)) {
            $this->doAddTransactionRelatedByPlayerA($l);

            if ($this->transactionsRelatedByPlayerAScheduledForDeletion and $this->transactionsRelatedByPlayerAScheduledForDeletion->contains($l)) {
                $this->transactionsRelatedByPlayerAScheduledForDeletion->remove($this->transactionsRelatedByPlayerAScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTransaction $transactionRelatedByPlayerA The ChildTransaction object to add.
     */
    protected function doAddTransactionRelatedByPlayerA(ChildTransaction $transactionRelatedByPlayerA)
    {
        $this->collTransactionsRelatedByPlayerA[]= $transactionRelatedByPlayerA;
        $transactionRelatedByPlayerA->setPlayerRelatedByPlayerA($this);
    }

    /**
     * @param  ChildTransaction $transactionRelatedByPlayerA The ChildTransaction object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removeTransactionRelatedByPlayerA(ChildTransaction $transactionRelatedByPlayerA)
    {
        if ($this->getTransactionsRelatedByPlayerA()->contains($transactionRelatedByPlayerA)) {
            $pos = $this->collTransactionsRelatedByPlayerA->search($transactionRelatedByPlayerA);
            $this->collTransactionsRelatedByPlayerA->remove($pos);
            if (null === $this->transactionsRelatedByPlayerAScheduledForDeletion) {
                $this->transactionsRelatedByPlayerAScheduledForDeletion = clone $this->collTransactionsRelatedByPlayerA;
                $this->transactionsRelatedByPlayerAScheduledForDeletion->clear();
            }
            $this->transactionsRelatedByPlayerAScheduledForDeletion[]= clone $transactionRelatedByPlayerA;
            $transactionRelatedByPlayerA->setPlayerRelatedByPlayerA(null);
        }

        return $this;
    }

    /**
     * Clears out the collTransactionsRelatedByPlayerB collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTransactionsRelatedByPlayerB()
     */
    public function clearTransactionsRelatedByPlayerB()
    {
        $this->collTransactionsRelatedByPlayerB = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTransactionsRelatedByPlayerB collection loaded partially.
     */
    public function resetPartialTransactionsRelatedByPlayerB($v = true)
    {
        $this->collTransactionsRelatedByPlayerBPartial = $v;
    }

    /**
     * Initializes the collTransactionsRelatedByPlayerB collection.
     *
     * By default this just sets the collTransactionsRelatedByPlayerB collection to an empty array (like clearcollTransactionsRelatedByPlayerB());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTransactionsRelatedByPlayerB($overrideExisting = true)
    {
        if (null !== $this->collTransactionsRelatedByPlayerB && !$overrideExisting) {
            return;
        }

        $collectionClassName = TransactionTableMap::getTableMap()->getCollectionClassName();

        $this->collTransactionsRelatedByPlayerB = new $collectionClassName;
        $this->collTransactionsRelatedByPlayerB->setModel('\App\Model\Transaction');
    }

    /**
     * Gets an array of ChildTransaction objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTransaction[] List of ChildTransaction objects
     * @throws PropelException
     */
    public function getTransactionsRelatedByPlayerB(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTransactionsRelatedByPlayerBPartial && !$this->isNew();
        if (null === $this->collTransactionsRelatedByPlayerB || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTransactionsRelatedByPlayerB) {
                // return empty collection
                $this->initTransactionsRelatedByPlayerB();
            } else {
                $collTransactionsRelatedByPlayerB = ChildTransactionQuery::create(null, $criteria)
                    ->filterByPlayerRelatedByPlayerB($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTransactionsRelatedByPlayerBPartial && count($collTransactionsRelatedByPlayerB)) {
                        $this->initTransactionsRelatedByPlayerB(false);

                        foreach ($collTransactionsRelatedByPlayerB as $obj) {
                            if (false == $this->collTransactionsRelatedByPlayerB->contains($obj)) {
                                $this->collTransactionsRelatedByPlayerB->append($obj);
                            }
                        }

                        $this->collTransactionsRelatedByPlayerBPartial = true;
                    }

                    return $collTransactionsRelatedByPlayerB;
                }

                if ($partial && $this->collTransactionsRelatedByPlayerB) {
                    foreach ($this->collTransactionsRelatedByPlayerB as $obj) {
                        if ($obj->isNew()) {
                            $collTransactionsRelatedByPlayerB[] = $obj;
                        }
                    }
                }

                $this->collTransactionsRelatedByPlayerB = $collTransactionsRelatedByPlayerB;
                $this->collTransactionsRelatedByPlayerBPartial = false;
            }
        }

        return $this->collTransactionsRelatedByPlayerB;
    }

    /**
     * Sets a collection of ChildTransaction objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $transactionsRelatedByPlayerB A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setTransactionsRelatedByPlayerB(Collection $transactionsRelatedByPlayerB, ConnectionInterface $con = null)
    {
        /** @var ChildTransaction[] $transactionsRelatedByPlayerBToDelete */
        $transactionsRelatedByPlayerBToDelete = $this->getTransactionsRelatedByPlayerB(new Criteria(), $con)->diff($transactionsRelatedByPlayerB);


        $this->transactionsRelatedByPlayerBScheduledForDeletion = $transactionsRelatedByPlayerBToDelete;

        foreach ($transactionsRelatedByPlayerBToDelete as $transactionRelatedByPlayerBRemoved) {
            $transactionRelatedByPlayerBRemoved->setPlayerRelatedByPlayerB(null);
        }

        $this->collTransactionsRelatedByPlayerB = null;
        foreach ($transactionsRelatedByPlayerB as $transactionRelatedByPlayerB) {
            $this->addTransactionRelatedByPlayerB($transactionRelatedByPlayerB);
        }

        $this->collTransactionsRelatedByPlayerB = $transactionsRelatedByPlayerB;
        $this->collTransactionsRelatedByPlayerBPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Transaction objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Transaction objects.
     * @throws PropelException
     */
    public function countTransactionsRelatedByPlayerB(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTransactionsRelatedByPlayerBPartial && !$this->isNew();
        if (null === $this->collTransactionsRelatedByPlayerB || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTransactionsRelatedByPlayerB) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTransactionsRelatedByPlayerB());
            }

            $query = ChildTransactionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayerRelatedByPlayerB($this)
                ->count($con);
        }

        return count($this->collTransactionsRelatedByPlayerB);
    }

    /**
     * Method called to associate a ChildTransaction object to this object
     * through the ChildTransaction foreign key attribute.
     *
     * @param  ChildTransaction $l ChildTransaction
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function addTransactionRelatedByPlayerB(ChildTransaction $l)
    {
        if ($this->collTransactionsRelatedByPlayerB === null) {
            $this->initTransactionsRelatedByPlayerB();
            $this->collTransactionsRelatedByPlayerBPartial = true;
        }

        if (!$this->collTransactionsRelatedByPlayerB->contains($l)) {
            $this->doAddTransactionRelatedByPlayerB($l);

            if ($this->transactionsRelatedByPlayerBScheduledForDeletion and $this->transactionsRelatedByPlayerBScheduledForDeletion->contains($l)) {
                $this->transactionsRelatedByPlayerBScheduledForDeletion->remove($this->transactionsRelatedByPlayerBScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTransaction $transactionRelatedByPlayerB The ChildTransaction object to add.
     */
    protected function doAddTransactionRelatedByPlayerB(ChildTransaction $transactionRelatedByPlayerB)
    {
        $this->collTransactionsRelatedByPlayerB[]= $transactionRelatedByPlayerB;
        $transactionRelatedByPlayerB->setPlayerRelatedByPlayerB($this);
    }

    /**
     * @param  ChildTransaction $transactionRelatedByPlayerB The ChildTransaction object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removeTransactionRelatedByPlayerB(ChildTransaction $transactionRelatedByPlayerB)
    {
        if ($this->getTransactionsRelatedByPlayerB()->contains($transactionRelatedByPlayerB)) {
            $pos = $this->collTransactionsRelatedByPlayerB->search($transactionRelatedByPlayerB);
            $this->collTransactionsRelatedByPlayerB->remove($pos);
            if (null === $this->transactionsRelatedByPlayerBScheduledForDeletion) {
                $this->transactionsRelatedByPlayerBScheduledForDeletion = clone $this->collTransactionsRelatedByPlayerB;
                $this->transactionsRelatedByPlayerBScheduledForDeletion->clear();
            }
            $this->transactionsRelatedByPlayerBScheduledForDeletion[]= clone $transactionRelatedByPlayerB;
            $transactionRelatedByPlayerB->setPlayerRelatedByPlayerB(null);
        }

        return $this;
    }

    /**
     * Clears out the collNotifications collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addNotifications()
     */
    public function clearNotifications()
    {
        $this->collNotifications = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collNotifications collection loaded partially.
     */
    public function resetPartialNotifications($v = true)
    {
        $this->collNotificationsPartial = $v;
    }

    /**
     * Initializes the collNotifications collection.
     *
     * By default this just sets the collNotifications collection to an empty array (like clearcollNotifications());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initNotifications($overrideExisting = true)
    {
        if (null !== $this->collNotifications && !$overrideExisting) {
            return;
        }

        $collectionClassName = NotificationTableMap::getTableMap()->getCollectionClassName();

        $this->collNotifications = new $collectionClassName;
        $this->collNotifications->setModel('\App\Model\Notification');
    }

    /**
     * Gets an array of ChildNotification objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildNotification[] List of ChildNotification objects
     * @throws PropelException
     */
    public function getNotifications(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationsPartial && !$this->isNew();
        if (null === $this->collNotifications || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collNotifications) {
                // return empty collection
                $this->initNotifications();
            } else {
                $collNotifications = ChildNotificationQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collNotificationsPartial && count($collNotifications)) {
                        $this->initNotifications(false);

                        foreach ($collNotifications as $obj) {
                            if (false == $this->collNotifications->contains($obj)) {
                                $this->collNotifications->append($obj);
                            }
                        }

                        $this->collNotificationsPartial = true;
                    }

                    return $collNotifications;
                }

                if ($partial && $this->collNotifications) {
                    foreach ($this->collNotifications as $obj) {
                        if ($obj->isNew()) {
                            $collNotifications[] = $obj;
                        }
                    }
                }

                $this->collNotifications = $collNotifications;
                $this->collNotificationsPartial = false;
            }
        }

        return $this->collNotifications;
    }

    /**
     * Sets a collection of ChildNotification objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $notifications A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setNotifications(Collection $notifications, ConnectionInterface $con = null)
    {
        /** @var ChildNotification[] $notificationsToDelete */
        $notificationsToDelete = $this->getNotifications(new Criteria(), $con)->diff($notifications);


        $this->notificationsScheduledForDeletion = $notificationsToDelete;

        foreach ($notificationsToDelete as $notificationRemoved) {
            $notificationRemoved->setPlayer(null);
        }

        $this->collNotifications = null;
        foreach ($notifications as $notification) {
            $this->addNotification($notification);
        }

        $this->collNotifications = $notifications;
        $this->collNotificationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Notification objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Notification objects.
     * @throws PropelException
     */
    public function countNotifications(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collNotificationsPartial && !$this->isNew();
        if (null === $this->collNotifications || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collNotifications) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getNotifications());
            }

            $query = ChildNotificationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collNotifications);
    }

    /**
     * Method called to associate a ChildNotification object to this object
     * through the ChildNotification foreign key attribute.
     *
     * @param  ChildNotification $l ChildNotification
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function addNotification(ChildNotification $l)
    {
        if ($this->collNotifications === null) {
            $this->initNotifications();
            $this->collNotificationsPartial = true;
        }

        if (!$this->collNotifications->contains($l)) {
            $this->doAddNotification($l);

            if ($this->notificationsScheduledForDeletion and $this->notificationsScheduledForDeletion->contains($l)) {
                $this->notificationsScheduledForDeletion->remove($this->notificationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildNotification $notification The ChildNotification object to add.
     */
    protected function doAddNotification(ChildNotification $notification)
    {
        $this->collNotifications[]= $notification;
        $notification->setPlayer($this);
    }

    /**
     * @param  ChildNotification $notification The ChildNotification object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removeNotification(ChildNotification $notification)
    {
        if ($this->getNotifications()->contains($notification)) {
            $pos = $this->collNotifications->search($notification);
            $this->collNotifications->remove($pos);
            if (null === $this->notificationsScheduledForDeletion) {
                $this->notificationsScheduledForDeletion = clone $this->collNotifications;
                $this->notificationsScheduledForDeletion->clear();
            }
            $this->notificationsScheduledForDeletion[]= clone $notification;
            $notification->setPlayer(null);
        }

        return $this;
    }

    /**
     * Gets a single ChildAccount object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildAccount
     * @throws PropelException
     */
    public function getAccount(ConnectionInterface $con = null)
    {

        if ($this->singleAccount === null && !$this->isNew()) {
            $this->singleAccount = ChildAccountQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleAccount;
    }

    /**
     * Sets a single ChildAccount object as related to this object by a one-to-one relationship.
     *
     * @param  ChildAccount $v ChildAccount
     * @return $this|\App\Model\Player The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccount(ChildAccount $v = null)
    {
        $this->singleAccount = $v;

        // Make sure that that the passed-in ChildAccount isn't already associated with this object
        if ($v !== null && $v->getPlayer(null, false) === null) {
            $v->setPlayer($this);
        }

        return $this;
    }

    /**
     * Clears out the collSessions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSessions()
     */
    public function clearSessions()
    {
        $this->collSessions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSessions collection loaded partially.
     */
    public function resetPartialSessions($v = true)
    {
        $this->collSessionsPartial = $v;
    }

    /**
     * Initializes the collSessions collection.
     *
     * By default this just sets the collSessions collection to an empty array (like clearcollSessions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSessions($overrideExisting = true)
    {
        if (null !== $this->collSessions && !$overrideExisting) {
            return;
        }

        $collectionClassName = SessionTableMap::getTableMap()->getCollectionClassName();

        $this->collSessions = new $collectionClassName;
        $this->collSessions->setModel('\App\Model\Session');
    }

    /**
     * Gets an array of ChildSession objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPlayer is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSession[] List of ChildSession objects
     * @throws PropelException
     */
    public function getSessions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSessionsPartial && !$this->isNew();
        if (null === $this->collSessions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSessions) {
                // return empty collection
                $this->initSessions();
            } else {
                $collSessions = ChildSessionQuery::create(null, $criteria)
                    ->filterByPlayer($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSessionsPartial && count($collSessions)) {
                        $this->initSessions(false);

                        foreach ($collSessions as $obj) {
                            if (false == $this->collSessions->contains($obj)) {
                                $this->collSessions->append($obj);
                            }
                        }

                        $this->collSessionsPartial = true;
                    }

                    return $collSessions;
                }

                if ($partial && $this->collSessions) {
                    foreach ($this->collSessions as $obj) {
                        if ($obj->isNew()) {
                            $collSessions[] = $obj;
                        }
                    }
                }

                $this->collSessions = $collSessions;
                $this->collSessionsPartial = false;
            }
        }

        return $this->collSessions;
    }

    /**
     * Sets a collection of ChildSession objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $sessions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function setSessions(Collection $sessions, ConnectionInterface $con = null)
    {
        /** @var ChildSession[] $sessionsToDelete */
        $sessionsToDelete = $this->getSessions(new Criteria(), $con)->diff($sessions);


        $this->sessionsScheduledForDeletion = $sessionsToDelete;

        foreach ($sessionsToDelete as $sessionRemoved) {
            $sessionRemoved->setPlayer(null);
        }

        $this->collSessions = null;
        foreach ($sessions as $session) {
            $this->addSession($session);
        }

        $this->collSessions = $sessions;
        $this->collSessionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Session objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Session objects.
     * @throws PropelException
     */
    public function countSessions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSessionsPartial && !$this->isNew();
        if (null === $this->collSessions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSessions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSessions());
            }

            $query = ChildSessionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPlayer($this)
                ->count($con);
        }

        return count($this->collSessions);
    }

    /**
     * Method called to associate a ChildSession object to this object
     * through the ChildSession foreign key attribute.
     *
     * @param  ChildSession $l ChildSession
     * @return $this|\App\Model\Player The current object (for fluent API support)
     */
    public function addSession(ChildSession $l)
    {
        if ($this->collSessions === null) {
            $this->initSessions();
            $this->collSessionsPartial = true;
        }

        if (!$this->collSessions->contains($l)) {
            $this->doAddSession($l);

            if ($this->sessionsScheduledForDeletion and $this->sessionsScheduledForDeletion->contains($l)) {
                $this->sessionsScheduledForDeletion->remove($this->sessionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSession $session The ChildSession object to add.
     */
    protected function doAddSession(ChildSession $session)
    {
        $this->collSessions[]= $session;
        $session->setPlayer($this);
    }

    /**
     * @param  ChildSession $session The ChildSession object to remove.
     * @return $this|ChildPlayer The current object (for fluent API support)
     */
    public function removeSession(ChildSession $session)
    {
        if ($this->getSessions()->contains($session)) {
            $pos = $this->collSessions->search($session);
            $this->collSessions->remove($pos);
            if (null === $this->sessionsScheduledForDeletion) {
                $this->sessionsScheduledForDeletion = clone $this->collSessions;
                $this->sessionsScheduledForDeletion->clear();
            }
            $this->sessionsScheduledForDeletion[]= clone $session;
            $session->setPlayer(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUnion) {
            $this->aUnion->removePlayer($this);
        }
        $this->id = null;
        $this->nickname = null;
        $this->gender = null;
        $this->union_id = null;
        $this->tags = null;
        $this->tags_unserialized = null;
        $this->address = null;
        $this->die_count = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collTimers) {
                foreach ($this->collTimers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTransactionsRelatedByPlayerA) {
                foreach ($this->collTransactionsRelatedByPlayerA as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTransactionsRelatedByPlayerB) {
                foreach ($this->collTransactionsRelatedByPlayerB as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collNotifications) {
                foreach ($this->collNotifications as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleAccount) {
                $this->singleAccount->clearAllReferences($deep);
            }
            if ($this->collSessions) {
                foreach ($this->collSessions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collTimers = null;
        $this->collTransactionsRelatedByPlayerA = null;
        $this->collTransactionsRelatedByPlayerB = null;
        $this->collNotifications = null;
        $this->singleAccount = null;
        $this->collSessions = null;
        $this->aUnion = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PlayerTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildPlayer The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PlayerTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    // validate behavior

    /**
     * Configure validators constraints. The Validator object uses this method
     * to perform object validation.
     *
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('nickname', new NotBlank());
        $metadata->addPropertyConstraint('address', new NotBlank());
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param      ValidatorInterface|null $validator A Validator class instance
     * @return     boolean Whether all objects pass validation.
     */
    public function validate(ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = new RecursiveValidator(
                new ExecutionContextFactory(new IdentityTranslator()),
                new LazyLoadingMetadataFactory(new StaticMethodLoader()),
                new ConstraintValidatorFactory()
            );
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            // If validate() method exists, the validate-behavior is configured for related object
            if (method_exists($this->aUnion, 'validate')) {
                if (!$this->aUnion->validate($validator)) {
                    $failureMap->addAll($this->aUnion->getValidationFailures());
                }
            }

            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collTimers) {
                foreach ($this->collTimers as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collTransactionsRelatedByPlayerA) {
                foreach ($this->collTransactionsRelatedByPlayerA as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collTransactionsRelatedByPlayerB) {
                foreach ($this->collTransactionsRelatedByPlayerB as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collNotifications) {
                foreach ($this->collNotifications as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collSessions) {
                foreach ($this->collSessions as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }

            $this->alreadyInValidation = false;
        }

        $this->validationFailures = $failureMap;

        return (Boolean) (!(count($this->validationFailures) > 0));

    }

    /**
     * Gets any ConstraintViolation objects that resulted from last call to validate().
     *
     *
     * @return     object ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
