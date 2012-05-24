<?php
/**
 * 
 * Represents a single record returned from a Bull_Model_Abstract.
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Model_Record
{
    const SQL_STATUS_DELETED    = 'deleted';
    const SQL_STATUS_INSERTED   = 'inserted';
    const SQL_STATUS_REFRESHED  = 'refreshed';
    const SQL_STATUS_ROLLBACK   = 'rollback';
    const SQL_STATUS_UNCHANGED  = 'unchanged';
    const SQL_STATUS_UPDATED    = 'updated';
    
    /**
     * 
     * The "parent" model for this record.
     * 
     * @var Bull_Model_Abstract
     * 
     */
    protected $model;
    
    /**
     * 
     * Tracks the the status *of this record* at the database.
     * 
     * Status values are:
     * 
     * `deleted`
     * : This record has been deleted; load(), etc. will not work.
     * 
     * `inserted`
     * : The record was inserted successfully.
     * 
     * `updated`
     * : The record was updated successfully.
     * 
     * @var string
     * 
     */
    protected $sql_status = null;
    
    /**
     * 
     * Tracks if *this record* is new (i.e., not in the database yet).
     * 
     * @var bool
     * 
     */
    protected $is_new = false;

    /**
     * 
     * Tracks if *this record* is dirty.
     * 
     * @var bool
     * 
     */
    protected $is_dirty = false;
    
    /**
     * 
     * If you call save() and an exception gets thrown, this stores that
     * exception.
     * 
     * @var Bull_Model_Exception
     * 
     */
    protected $save_exception;
    
    /**
     * 
     * An array of the initial (clean) data for the record.
     * 
     * This tracks only table-column data.
     * 
     * @var array
     * 
     * @see setStatus()
     * 
     */
    protected $initial = array();

    /**
     *
     * Record data. 
     *
     */
    protected $data = array();

    /**
     * 
     * Magic getter for record properties; automatically calls __getColName()
     * methods when they exist.
     * 
     * @param string $key The property name.
     * 
     * @return mixed The property value.
     * 
     */
    public function __get($key)
    {
        $found = array_key_exists($key, $this->data);
        if (! $found && ! empty($this->model->related[$key])) {
            // the key is for a related that has no data yet.
            // get the relationship object and get the related object
            $related = $this->model->getRelated($key);
            $this->data[$key] = $related->fetch($this);
        }
        
        // 有必要使用Closure么？
        if ($this->data[$key] instanceof Closure) {
            $this->data[$key] = $this->data[$key]();
        }
        
        return $this->data[$key];
    }
    
    /**
     * 
     * Magic setter for record properties; automatically calls __setColName()
     * methods when they exist.
     * 
     * @param string $key The property name.
     * 
     * @param mixed $val The value to set.
     * 
     * @return void
     * 
     */
    public function __set($key, $val)
    {
        $this->data[$key] = $val;
        $this->setIsDirty();
    }
    
    /**
     * 
     * Sets a key in the data to null.
     * 
     * @param string $key The requested data key.
     * 
     * @return void
     * 
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
        $this->setIsDirty();
    }
    
    /**
     * 
     * Checks if a data key is set.
     * 
     * @param string $key The requested data key.
     * 
     * @return void
     * 
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }


    public function toArray()
    {
        return $this->getInsertData();
    }
    /**
     * 
     * Loads data from an array.
     * 
     * Also unserializes columns per the "serialize_cols" model property.
     * 
     * @param array $spec The data to load into the object.
     * 
     * @param array $cols Load only these columns.
     * 
     * @return void
     * 
     */
    public function load($spec, $cols = null)
    {
        // force to array
        if (is_array($spec)) {
            $load = $spec;
        } else {
            $load = array();
        }
        // remove any load columns not in the whitelist
        if (! empty($cols)) {
            $cols = (array) $cols;
            foreach ($load as $key => $val) {
                if (! in_array($key, $cols)) {
                    unset($load[$key]);
                }
            }
        }
        // Set values
        foreach ($load as $key => $value) {
            $this->$key = $value;
        }

        $this->fixRelatedData();
    }
    
    // -----------------------------------------------------------------
    //
    // Model
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * Returns the model from which the data originates.
     * 
     * @return Bull_Model_Abstract $model The origin model object.
     * 
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * 
     * Gets the name of the primary-key column.
     * 
     * @return string
     * 
     */
    public function getPrimaryCol()
    {
        return $this->model->primary();
    }
    
    /**
     * 
     * Gets the value of the primary-key column.
     * 
     * @return mixed
     * 
     */
    public function getPrimaryVal()
    {
        $col = $this->model->primary();
        return $this->$col;
    }
    
    // -----------------------------------------------------------------
    //
    // save, insert, update, delete, refresh.
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * Saves this record and all related records to the database, inserting or
     * updating as needed.
     * 
     * @param array $data An associative array of data to merge with existing
     * record data.
     * 
     * @return bool True on success, false on failure.
     * 
     */
    public function save($data = null)
    {
        if ($this->isDeleted()) {
            throw new Bull_Model_Exception('ERR_DELETED');
        }
        
        $this->save_exception = null;
        
        // load data at save-time?
        if ($data) {
            $this->load($data);
            $this->setIsDirty();
        }
        try {
            $this->_save();
            $this->saveRelated();
            if ($this->model->filter->isFailure()) {
                return false;
            } else {
                return true;
            }
        } catch (Bull_Model_Exception_RecordInvalid $e) {
            // filtering should already have set the invalid messages
            $this->save_exception = $e;
            return false;
        }
    }
    
    /**
     * 
     * Saves the current record, but only if the record is "dirty".
     * 
     * On saving, invokes the pre-save, pre- and post- insert/update,
     * and post-save hooks.
     * 
     * @return void
     * 
     */
    protected function _save()
    {
        // only save if need to
        if ($this->isDirty() || $this->isNew()) {
            // perform pre-save for any relateds that need to modify the 
            // native record, but only if instantiated
            $list = array_keys($this->model->related);
            foreach ($list as $name) {
                if (! empty($this->data[$name])) {
                    $this->model->getRelated($name)->preSave($this);
                }
            }
            // insert or update based on newness
            if ($this->isNew()) {
                $this->insert();
            } else {
                $this->update();
            }
        }
    }
    
    /**
     * 
     * Inserts the current record into the database, making calls to pre- and
     * post-insert logic.
     * 
     * @return void
     * 
     */
    protected function insert()
    {
        // modify special columns for insert
        $this->modInsert();
        // get the data for insert
        $data = $this->getInsertData();
        // apply record filters
        $this->filter($data);

        // try the insert
        try {
            // retain the inserted ID, if any
            $this->model->insert($data);
            $id = $this->model->lastInsertId();
        } catch (Bull_Model_Exception_QueryFailed $e) {
            // failed at at the database for some reason
            $this->modle->filter->setInvalid('*', $e->getInfo('pdo_text'));
            throw $e;
        }
        // if there is an autoinc column, set its value
        foreach ($this->model->cols as $col => $info) {
            if ($info->autoinc && empty($this->data[$col])) {
                // set the value ...
                $this->data[$col] = $id;
                // ... and skip all other cols
                break;
            }
        }
        
        // record was successfully inserted
        $this->setSqlStatus(self::SQL_STATUS_INSERTED);
    }
    
    /**
     * 
     * Modify the current record before it is inserted into the DB.
     * 
     * @return void
     * 
     */
    protected function modInsert()
    {
        // time right now for created/updated
        $now = date('Y-m-d H:i:s');
        
        // force the 'created' value if there is a 'created' column
        $col = $this->model->created_col;
        if ($col) {
            $this->$col = $now;
        }
        
        // force the 'updated' value if there is an 'updated' column
        $col = $this->model->updated_col;
        if ($col) {
            $this->$col = $now;
        }
    }
    
    /**
     * 
     * Gather values to insert into the DB for a new record.
     * 
     * @return array The values to be inserted.
     * 
     */
    protected function getInsertData()
    {
        // get only table columns
        $data = array();
        $cols = array_keys($this->model->columns());
        foreach ($this->data as $col => $val) {
            if (in_array($col, $cols)) {
                $data[$col] = $val;
            }
        }
        // done
        return $data;
    }
    
    /**
     * 
     * Updates the current record at the database, making calls to pre- and
     * post-update logic.
     * 
     * @return void
     * 
     */
    protected function update()
    {
        // modify special columns for update
        $this->modUpdate();
        
        // get the data for update
        $data = $this->getUpdateData();

        // it's possible we have no data to update, even after all that
        if (! $data) {
            $this->setSqlStatus(self::SQL_STATUS_UNCHANGED);
            return;
        }

        // apply record filters
        $this->filter($data);
        
        // build the where clause
        $primary = $this->getPrimaryCol();
        $where   = "$primary = :primary";
        $ext     = array('primary' => $this->getPrimaryVal());
        
        // try the update
        try {
            $this->model->update($data, $where, $ext);
        } catch (Bull_Model_Exception_QueryFailed $e) {
            // failed at at the database for some reason
            $this->model->filter->setInvalid('*', $e->getInfo('pdo_text'));
            throw $e;
        }
        // record was successfully updated
        $this->setSqlStatus(self::SQL_STATUS_UPDATED);
    }
    
    /**
     * 
     * Modify the current record before it is updated into the DB.
     * 
     * @return void
     * 
     */
    protected function modUpdate()
    {
        // force the 'updated' value
        $col = $this->model->updated_col;
        if ($col) {
            $this->$col = date('Y-m-d H:i:s');
        }
    }
    
    /**
     * 
     * Gather values to update into the DB.  Only values that have
     * Changed will be updated
     * 
     * @return array values that should be updated
     * 
     */
    protected function getUpdateData()
    {
        // get only table columns that have changed
        $data = array();
        $cols = array_keys($this->model->columns());
        foreach ($this->data as $col => $val) {
            if (in_array($col, $cols) && $this->isChanged($col)) {
                $data[$col] = $val;
            }
        }
        // done!
        return $data;
    }
    
    /**
     * 
     * Saves each related record.
     * 
     * Invokes the pre- and post- saveRelated methods.
     * 
     * @return void
     * 
     * @todo Keep track of invalid saves on related records and collections?
     * 
     */
    protected function saveRelated()
    {
        // save each related
        $list = array_keys($this->model->related);
        foreach ($list as $name) {
            // only save if instantiated
            if (! empty($this->data[$name])) {
                // get the relationship object and save the related
                $related = $this->model->getRelated($name);
                $related->save($this);
            }
        }
    }
    
    /**
     * 
     * Deletes this record from the database.
     * 
     * @return void
     * 
     */
    public function delete()
    {
        if ($this->isNew()) {
            throw new Bull_Model_Exception('ERR_CANNOT_DELETE_NEW_RECORD');
        }
        
        if ($this->isDeleted()) {
            throw new Bull_Model_Exception('ERR_DELETED');
        }
        
        $primary = $this->getPrimaryCol();
        $where = array(
            "$primary = ?" => $this->getPrimaryVal(),
        );
        $this->model->delete($where);
        $this->setSqlStatus(self::SQL_STATUS_DELETED);
    }
    
    /**
     * 
     * Filter the data.
     * 
     * @return void
     * 
     */
    public function filter()
    {
        // @TODO: If failure, throw new Bull_Model_Exception();
    }
    
    // -----------------------------------------------------------------
    //
    // 当状单条记录状态
    //
    // -----------------------------------------------------------------

    /**
     * 
     * Is the record new?
     * 
     * @return bool
     * 
     */
    public function isNew()
    {
        return (bool) $this->is_new;
    }

    /**
     * 
     * Has this record been deleted?
     * 
     * @return bool
     * 
     */
    public function isDeleted()
    {
        return $this->sql_status == self::SQL_STATUS_DELETED;
    }

    public function isDirty()
    {
        return (bool) $this->is_dirty;
    }

    protected function setIsNew()
    {
        $this->is_new = true;
    }
    
    /**
     * 
     * Marks the record as dirty.
     * 
     * @return void
     * 
     */
    protected function setIsDirty()
    {
        $this->is_dirty = true;
    }
    
    /**
     * 
     * Returns the SQL status of this record at the database.
     * 
     * @return string The status value.
     * 
     */
    public function getSqlStatus()
    {
        return $this->sql_status;
    }
    
    /**
     * 
     * Sets the SQL status of this record, resetting dirty/new/invalid as
     * needed.
     * 
     * @param string $sql_status The new status to set on this record.
     * 
     * @return void
     * 
     */
    protected function setSqlStatus($sql_status)
    {
        // is this a change in status?
        if ($sql_status == $this->sql_status) {
            // no change, we're done
            return;
        }
        
        // set the new status
        $this->sql_status = $sql_status;
        
        // should we reset other information?
        $reset = in_array($this->sql_status, array(
            self::SQL_STATUS_INSERTED,
            self::SQL_STATUS_REFRESHED,
            self::SQL_STATUS_UNCHANGED,
            self::SQL_STATUS_UPDATED,
        ));
        
        if ($reset) {
            
            // reset the initial data for table columns
            $this->initial = array_intersect_key(
                $this->data,
                $this->model->cols
            );
            
            // no longer invalid, dirty, or new
            $this->is_dirty = false;
            $this->is_new = false;
        }
    }
    
    /**
     * 
     * Tells if the record, or a particular table-column in the record, has
     * changed from its initial value.
     * 
     * This is slightly complicated.  Changes to or from a null are reported
     * as "changed".  If both the initial value and new value are numeric
     * (that is, whether they are string/float/int), they are compared using
     * normal inequality (!=).  Otherwise, the initial value and new value
     * are compared using strict inequality (!==).
     * 
     * This complexity results from converting string and numeric values in
     * and out of the database.  Coming from the database, a string numeric
     * '1' might be filtered to an integer 1 at some point, making it look
     * like the value was changed when in practice it has not.
     * 
     * Similarly, we need to make allowances for nulls, because a non-numeric
     * null is loosely equal to zero or an empty string.
     * 
     * @param string $col The table-column name; if null, 
     * 
     * @return void|bool Returns null if the table-column name does not exist,
     * boolean true if the data is changed, boolean false if not changed.
     * 
     * @todo How to handle changes to array values?
     * 
     */
    public function isChanged($col = null)
    {
        // if no column specified, check if the record as a whole has changed
        if ($col === null) {
            foreach ($this->initial as $col => $val) {
                if ($this->isChanged($col)) {
                    return true;
                }
            }
            return false;
        }
        
        // col needs to exist in the initial array
        if (! array_key_exists($col, $this->initial)) {
            return null;
        }
        
        // track changes to or from null
        $from_null = $this->initial[$col] === null &&
                     $this->data[$col] !== null;
        
        $to_null   = $this->initial[$col] !== null &&
                     $this->data[$col] === null;
        
        if ($from_null || $to_null) {
            return true;
        }
        
        // track numeric changes
        $both_numeric = is_numeric($this->initial[$col]) &&
                        is_numeric($this->data[$col]);
        if ($both_numeric) {
            // use normal inequality
            return $this->initial[$col] != (string) $this->data[$col];
        }
        
        // use strict inequality
        return $this->initial[$col] !== $this->data[$col];
    }
    
    /**
     * 
     * Gets a list of all changed table columns.
     * 
     * @return array
     * 
     */
    public function getChanged()
    {
        $list = array();
        foreach ($this->initial as $col => $val) {
            if ($this->isChanged($col)) {
                $list[] = $col;
            }
        }
        return $list;
    }

    /**
     * 
     * Returns the exception (if any) generated by the most-recent call to the
     * save() method.
     * 
     * @return Exception
     * 
     * @see save()
     * 
     */
    public function getSaveException()
    {
        return $this->save_exception;
    }

    /**
     * 
     * Is the record or one of its relateds invalid?
     * 
     * @return bool
     * 
     */
    public function isInvalid()
    {
        if ($this->model->filter->isFailure()) {
            // one or more properties on this record is invalid.
            // although we could use _getInvalid() here, this is
            // a quick shortcut for common cases.
            return true;
        } elseif ($this->sql_status == self::SQL_STATUS_ROLLBACK) {
            // we had a rollback, so *something* is invalid
            return true;
        } else {
            // looks like nothing is invalid
            return false;
        }
    }

    /**
     * 
     * Create a new record/collection related to this one and returns it.
     * 
     * @param string $name The relation name.
     * 
     * @param array $data Initial data.
     * 
     * @return Bull_Model_Record|Bull_Model_Collection
     * 
     */
    public function newRelated($name, $data = null)
    {
        $related = $this->model->getRelated($name);
        $new = $related->newRecord($data);
        return $new;
    }
    
    /**
     * 
     * Sets the related to be a new record/collection, but only if the
     * related is empty.
     * 
     * @param string $name The relation name.
     * 
     * @param array $data Initial data.
     * 
     * @return Bull_Model_Record|Bull_Model_Collection
     * 
     */
    public function setNewRelated($name, $data = null)
    {
        if ($this->$name) {
            throw new Bull_Model_Exception('ERR_RELATED_ALREADY_SET');
        }
        $this->$name = $this->newRelated($name, $data);
        return $this->$name;
    }
    
    /**
     * 
     * Initialize the record object.  This is effectively a "first load"
     * method.
     * 
     * @param Bull_Model_Abstract $model The originating model object instance (a
     * dependency injection).
     * 
     * @param array $spec The data with which to initialize this record.
     * 
     * @return void
     * 
     */
    public function init(Bull_Model_Abstract $model, array $spec)
    {
        if ($this->model) {
            throw new Bull_Model_Exception('ERR_CANNOT_REINIT');
        }
        
        // inject the model
        $this->model = $model;

        // data
        $this->data = $spec;
        
        // Record the inital values but only for columns that have physical backing
        $this->initial = array_intersect_key($spec, $model->cols);

        // fix up related data elements
        $this->fixRelatedData();
        
        // new?
        $this->is_new = false;
        
        // can't be dirty
        $this->is_dirty = false;
        
        // no last sql status
        $this->sql_status = null;
    }
    
    /**
     * 
     * Initialize the record object as a "new" record; as with init(), this is
     * effectively a "first load" method.
     * 
     * @param Bull_Model_Abstract $model The originating model object instance (a
     * dependency injection).
     * 
     * @param array $spec The data with which to initialize this record.
     * 
     * @return void
     * 
     * @see init()
     * 
     */
    public function initNew()
    {
        $this->is_new = true;
    }

    /**
     * 
     * Make sure our related data values are the right value and type.
     * 
     * Make sure our related objects are the right type or will be loaded when
     * necessary
     * 
     * @return void
     * 
     */
    protected function fixRelatedData()
    {
        $list = array_keys($this->model->related);
        
        foreach ($list as $name) {
            // convert related values to correct object type
            $convert = array_key_exists($name, $this->data)
                    && ! is_object($this->data[$name]);
            
            if (! $convert) {
                continue;
            }
            $related = $this->model->getRelated($name);
            if (empty($this->data[$name])) {
                $this->data[$name] = $related->fetchEmpty();
            } else {
                $this->data[$name] = $related->fetchNew($this->data[$name]);
            }
        }
    }
    
    public function free()
    {
        unset($this->data);
    }
    
    public function __call($method, $args)
    {
        if (is_callable(array($this, $method))) {
            return call_user_func_array($this->$method, $args);            
        }
    }
}
