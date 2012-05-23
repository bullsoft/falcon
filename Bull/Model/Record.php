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
    protected $_model;
    
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
    protected $_sql_status = null;
    
    /**
     * 
     * Tracks if *this record* is new (i.e., not in the database yet).
     * 
     * @var bool
     * 
     */
    protected $_is_new = false;

    protected $_is_dirty = false;
    /**
     * 
     * If you call save() and an exception gets thrown, this stores that
     * exception.
     * 
     * @var Bull_Model_Exception
     * 
     */
    protected $_save_exception;
    
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
    protected $_initial = array();

    /**
     *
     * Record data. 
     *
     */
    protected $_data = array();

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
        $found = array_key_exists($key, $this->_data);
        if (! $found && ! empty($this->_model->related[$key])) {
            // the key is for a related that has no data yet.
            // get the relationship object and get the related object
            $related = $this->_model->getRelated($key);
            $this->_data[$key] = $related->fetch($this);
        }
        return $this->_data[$key];
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
        $this->_data[$key] = $val;
        $this->_setIsDirty();
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
        unset($this->_data[$key]);
        $this->_setIsDirty();
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
        return isset($this->_data[$key]);
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
        return $this->_model;
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
        return $this->_model->primary();
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
        $col = $this->_model->primary();
        return $this->$col;
    }
    
    // -----------------------------------------------------------------
    //
    // Persistence: save, insert, update, delete, refresh.
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * Saves this record and all related records to the database, inserting or
     * updating as needed.
     * 
     * Hook methods:
     * 
     * 1. `_preSave()` runs before all save operations.
     * 
     * 2. `_preInsert()` and `_preUpdate()` run before the insert or update.
     * 
     * 3. As part of the model insert()/update() logic, `filter()` gets called,
     *    which itself has `_preFilter()` and `_postFilter()` hooks.
     *    
     * 4. `_postInsert()` and `_postUpdate()` run after the insert or update.
     * 
     * 5. `_postSave()` runs after all save operations, but before related
     *    records are saved.
     * 
     * 6. `_preSaveRelated()` runs before saving related records.
     * 
     * 7. Each related record is saved, invoking the save() routine with all
     *    its hooks on each related record.
     * 
     * 8. `_postSaveRelated()` runs after all related records are saved.
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
        
        $this->_save_exception = null;
        
        // load data at save-time?
        if ($data) {
            $this->load($data);
            $this->_setIsDirty();
        }
        try {
            $this->_save();
            $this->_saveRelated();
            if ($this->_model->filter->isFailure()) {
                return false;
            } else {
                return true;
            }
        } catch (Bull_Model_Exception_RecordInvalid $e) {
            // filtering should already have set the invalid messages
            $this->_save_exception = $e;
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
            // pre-save routine
            $this->_preSave();
            // perform pre-save for any relateds that need to modify the 
            // native record, but only if instantiated
            $list = array_keys($this->_model->related);
            foreach ($list as $name) {
                if (! empty($this->_data[$name]) && ! is_object($this->_data[$name])) {
                    $this->_model->getRelated($name)->preSave($this, $this->_data[$name]);
                }
            }
            // insert or update based on newness
            if ($this->isNew()) {
                $this->_insert();
            } else {
                $this->_update();
            }
            // post-save routine
            $this->_postSave();
        }
    }
    
    /**
     * 
     * User-defined pre-save logic.
     * 
     * @return void
     * 
     */
    protected function _preSave()
    {
    }
    
    /**
     * 
     * User-defined post-save logic.
     * 
     * @return void
     * 
     */
    protected function _postSave()
    {
    }
    
    /**
     * 
     * Inserts the current record into the database, making calls to pre- and
     * post-insert logic.
     * 
     * @return void
     * 
     */
    protected function _insert()
    {
        // pre-insert logic
        $this->_preInsert();
        // modify special columns for insert
        $this->_modInsert();
        // get the data for insert
        $data = $this->_getInsertData();
        // apply record filters
        $this->filter($data);

        // try the insert
        try {
            // retain the inserted ID, if any
            $this->_model->insert($data);
            $id = $this->_model->lastInsertId();
        } catch (Bull_Model_Exception_QueryFailed $e) {
            // failed at at the database for some reason
            $this->_modle->filter->setInvalid('*', $e->getInfo('pdo_text'));
            throw $e;
        }
        // if there is an autoinc column, set its value
        foreach ($this->_model->cols as $col => $info) {
            if ($info->autoinc && empty($this->_data[$col])) {
                // set the value ...
                $this->_data[$col] = $id;
                // ... and skip all other cols
                break;
            }
        }
        
        // record was successfully inserted
        $this->_setSqlStatus(self::SQL_STATUS_INSERTED);
        
        // post-insert logic
        $this->_postInsert();
    }
    
    /**
     * 
     * Modify the current record before it is inserted into the DB.
     * 
     * @return void
     * 
     */
    protected function _modInsert()
    {
        // time right now for created/updated
        $now = date('Y-m-d H:i:s');
        
        // force the 'created' value if there is a 'created' column
        $col = $this->_model->created_col;
        if ($col) {
            $this->$col = $now;
        }
        
        // force the 'updated' value if there is an 'updated' column
        $col = $this->_model->updated_col;
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
    protected function _getInsertData()
    {
        // get only table columns
        $data = array();
        $cols = array_keys($this->_model->columns());
        foreach ($this->_data as $col => $val) {
            if (in_array($col, $cols)) {
                $data[$col] = $val;
            }
        }
        // done
        return $data;
    }
    
    /**
     * 
     * User-defined pre-insert logic.
     * 
     * @return void
     * 
     */
    protected function _preInsert() {}
    
    /**
     * 
     * User-defined post-insert logic.
     * 
     * @return void
     * 
     */
    protected function _postInsert() {}
    
    /**
     * 
     * Updates the current record at the database, making calls to pre- and
     * post-update logic.
     * 
     * @return void
     * 
     */
    protected function _update()
    {
        // pre-update logic
        $this->_preUpdate();
        
        // modify special columns for update
        $this->_modUpdate();
        
        // get the data for update
        $data = $this->_getUpdateData();

        // it's possible we have no data to update, even after all that
        if (! $data) {
            $this->_setSqlStatus(self::SQL_STATUS_UNCHANGED);
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
            $this->_model->update($data, $where, $ext);
        } catch (Bull_Model_Exception_QueryFailed $e) {
            // failed at at the database for some reason
            $this->_model->filter->setInvalid('*', $e->getInfo('pdo_text'));
            throw $e;
        }
        // record was successfully updated
        $this->_setSqlStatus(self::SQL_STATUS_UPDATED);

        // post-update logic
        $this->_postUpdate();
    }
    
    /**
     * 
     * Modify the current record before it is updated into the DB.
     * 
     * @return void
     * 
     */
    protected function _modUpdate()
    {
        // force the 'updated' value
        $col = $this->_model->updated_col;
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
    protected function _getUpdateData()
    {
        // get only table columns that have changed
        $data = array();
        $cols = array_keys($this->_model->columns());
        foreach ($this->_data as $col => $val) {
            if (in_array($col, $cols) && $this->isChanged($col)) {
                $data[$col] = $val;
            }
        }
        // done!
        return $data;
    }
    
    /**
     * 
     * User-defined pre-update logic.
     * 
     * @return void
     * 
     */
    protected function _preUpdate() {}
    
    /**
     * 
     * User-defined post-update logic.
     * 
     * @return void
     * 
     */
    protected function _postUpdate() {}
    
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
    protected function _saveRelated()
    {
        // pre-hook
        $this->_preSaveRelated();
        // save each related
        $list = array_keys($this->_model->related);
        foreach ($list as $name) {
            // only save if instantiated
            if (! empty($this->_data[$name]) && !is_object($this->_data[$name])) {
                // get the relationship object and save the related
                $related = $this->_model->getRelated($name);
                $related->save($this, $this->_data[$name]);
            }
        }
        // post-hook
        $this->_postSaveRelated();
    }
    
    /**
     * 
     * User-defined logic to execute before saving related records.
     * 
     * @return void
     * 
     */
    protected function _preSaveRelated() {}
    
    /**
     * 
     * User-defined logic to execute after saving related records.
     * 
     * @return void
     * 
     */
    protected function _postSaveRelated() {}
    
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
        
        $this->_preDelete();
        
        $primary = $this->getPrimaryCol();
        $where = array(
            "$primary = ?" => $this->getPrimaryVal(),
        );
        $this->_model->delete($where);
        $this->_setSqlStatus(self::SQL_STATUS_DELETED);
        $this->_postDelete();
    }
    
    /**
     * 
     * User-defined pre-delete logic.
     * 
     * @return void
     * 
     */
    protected function _preDelete() {}
    
    /**
     * 
     * User-defined post-delete logic.
     * 
     * @return void
     * 
     */
    protected function _postDelete() {}
    
    /**
     * 
     * Filter the data.
     * 
     * @param Solar_Filter $filter Use this filter instead of the default one.
     * When empty (the default), uses the default filter for the record.
     * 
     * @return void
     * 
     */
    public function filter()
    {
        // @TODO:
    }
    
    // -----------------------------------------------------------------
    //
    // Record status
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
        return (bool) $this->_is_new;
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
        return $this->_sql_status == self::SQL_STATUS_DELETED;
    }

    public function isDirty()
    {
        return (bool) $this->_is_dirty;
    }
    
    /**
     * 
     * Marks the struct as dirty.
     * 
     * @return void
     * 
     */
    protected function _setIsDirty()
    {
        $this->_is_dirty = true;
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
        return $this->_sql_status;
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
    protected function _setSqlStatus($sql_status)
    {
        // is this a change in status?
        if ($sql_status == $this->_sql_status) {
            // no change, we're done
            return;
        }
        
        // set the new status
        $this->_sql_status = $sql_status;
        
        // should we reset other information?
        $reset = in_array($this->_sql_status, array(
            self::SQL_STATUS_INSERTED,
            self::SQL_STATUS_REFRESHED,
            self::SQL_STATUS_UNCHANGED,
            self::SQL_STATUS_UPDATED,
        ));
        
        if ($reset) {
            
            // reset the initial data for table columns
            $this->_initial = array_intersect_key(
                $this->_data,
                $this->_model->cols
            );
            
            // no longer invalid, dirty, or new
            $this->_is_dirty = false;
            $this->_is_new = false;
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
            foreach ($this->_initial as $col => $val) {
                if ($this->isChanged($col)) {
                    return true;
                }
            }
            return false;
        }
        
        // col needs to exist in the initial array
        if (! array_key_exists($col, $this->_initial)) {
            return null;
        }
        
        // track changes to or from null
        $from_null = $this->_initial[$col] === null &&
                     $this->_data[$col] !== null;
        
        $to_null   = $this->_initial[$col] !== null &&
                     $this->_data[$col] === null;
        
        if ($from_null || $to_null) {
            return true;
        }
        
        // track numeric changes
        $both_numeric = is_numeric($this->_initial[$col]) &&
                        is_numeric($this->_data[$col]);
        if ($both_numeric) {
            // use normal inequality
            return $this->_initial[$col] != (string) $this->_data[$col];
        }
        
        // use strict inequality
        return $this->_initial[$col] !== $this->_data[$col];
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
        foreach ($this->_initial as $col => $val) {
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
        return $this->_save_exception;
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
        if ($this->_model->filter->isFailure()) {
            // one or more properties on this record is invalid.
            // although we could use _getInvalid() here, this is
            // a quick shortcut for common cases.
            return true;
        } elseif ($this->_sql_status == self::SQL_STATUS_ROLLBACK) {
            // we had a rollback, so *something* is invalid
            return true;
        } else {
            // looks like nothing is invalid
            return false;
        }
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
    protected function _fixRelatedData()
    {
        $list = array_keys($this->_model->related);
        
        foreach ($list as $name) {
            // convert related values to correct object type
            $convert = array_key_exists($name, $this->_data)
                    && ! is_array($this->_data[$name]);
            
            if (! $convert) {
                continue;
            }
            
            $related = $this->_model->getRelated($name);
            if (is_null($this->_data[$name])) {
                $this->_data[$name] = $related->fetch($this->_data);
            }
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
     * @return Bull_Model_Record
     * 
     */
    public function newRelated($name, $data = null)
    {
        $related = $this->_model->getRelated($name);
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
     * @return Bull_Model_Record
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
    public function init(Bull_Model_Abstract $model, array $data=array())
    {
        if ($this->_model) {
            throw new Bull_Model_Exception('ERR_CANNOT_REINIT');
        }
        
        // inject the model
        $this->_model = $model;

        // data
        $this->_data = $data;
        
        // Record the inital values but only for columns that have physical backing
        $this->_initial = array_intersect_key($data, $model->cols);

        // fix up related data elements
        $this->_fixRelatedData();
        
        // new?
        $this->_is_new = false;
        
        // can't be dirty
        $this->_is_dirty = false;
        
        // no last sql status
        $this->_sql_status = null;
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
        // $this->init($model, $spec);
        $this->_is_new = true;
    }

    public function  free()
    {
        unset($this->_data);
    }
    
    public function __call($method, $args)
    {
        if (is_callable(array($this, $method))) {
            return call_user_func_array($this->$method, $args);            
        }
    }
}
