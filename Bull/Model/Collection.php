<?php
/**
 * 
 * Represents a collection of Bull_Model_Record objects.
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Model_Collection implements ArrayAccess, Countable, Iterator
{
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
     * Data of-course as you know.
     *
     * @var array
     *
     */
    protected $data;

    /**
     *
     * Iterator: is this *postiont* valid?
     *
     * @bool
     *
     */
    protected $valid;

    /**
     * 
     * When calling save(), these are the data keys that were invalid and thus
     * not fully saved.
     * 
     * @var mixed
     * 
     * @see save()
     * 
     */
    protected $invalid_offsets = array();
    
    /**
     * 
     * Returns a record from the collection based on its key value.  Converts
     * the stored data array to a record of the correct class on-the-fly.
     * 
     * @param int|string $key The sequential or associative key value for the
     * record.
     * 
     * @return Bull_Model_Record
     * 
     */
    public function __get($key)
    {
        if (! $this->__isset($key)) {
            // create a new blank record for the missing key
            $this->data[$key] = $this->model->newRecord();
        }

        // convert array to record object.
        if (is_array($this->data[$key])) {
            // convert the data array to an object.
            // get the main data to load to the record.
            $load = $this->data[$key];
            $this->data[$key] = $this->model->newRecord($load);
        }
        
        // return the record
        return $this->data[$key];
    }
    
    /**
     * 
     * Does a certain key exist in the data?
     * 
     * Note that this is slightly different from normal PHP isset(); it will
     * say the key is set, even if the key value is null or otherwise empty.
     * 
     * @param string $key The requested data key.
     * 
     * @return void
     * 
     */
    public function __isset($key)
    {
        return array_key_exists($key, $this->data);
    }
    
    /**
     * 
     * Returns an array of the unique primary keys contained in this 
     * collection. Will not cause records to be created for as of yet 
     * unaccessed rows.
     * 
     * @param string $col The column to look for; when null, uses the model
     * primary-key column.
     *
     * @return array
     * 
     */
    public function getPrimaryVals($col = null)
    {
        // what key to look for?
        if (empty($col)) {
            $col = $this->model->primary();
        }
        
        // get all key values
        $list = array();
        foreach ($this->data as $key => $val) {
            $list[$key] = $val[$col];
        }
        
        // done!
        return $list;
    }
    
    /**
     * 
     * Returns an array of all values for a single column in the collection.
     *
     * @param string $col The column name to retrieve values for.
     *
     * @return array An array of key-value pairs where the key is the
     * collection element key, and the value is the column value for that
     * element.
     * 
     */
    public function getColVals($col)
    {
        $list = array();
        foreach ($this as $key => $record) {
            $list[$key] = $record->$col;
        }
        return $list;
    }
    
    /**
     * 
     * Injects the model from which the data originates.
     * 
     * Also loads accessor method lists for column and related properties.
     * 
     * These let users override how the column properties are accessed
     * through the magic __get, __set, etc. methods.
     * 
     * @param Bull_Model_Abstract $model The origin model object.
     * 
     * @return void
     * 
     */
    public function setModel(Bull_Model_Abstract $model)
    {
        $this->model = $model;
    }
    
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
     * Loads the struct with data from an array or another struct.
     * 
     * This is a complete override from the parent load() method.
     * 
     * We need this so that fetchAssoc() loading works properly; otherwise, 
     * integer keys get renumbered, which disconnects the association.
     * 
     * @param array $spec The data to load into the object.
     * 
     * @return void
     * 
     */
    public function load($spec)
    {
        if (is_array($spec)) {
            $this->data = $spec;
        } else {
            $this->data = array();
        }
    }

    /**
     * 
     * Returns the data for each record in this collection as an array.
     * 
     * @return array
     * 
     */
    public function toArray()
    {
        $data = array();
        foreach ($this as $key => $record) {
            $data[$key] = $record->toArray();
        }
        return $data;
    }
    
    /**
     * 
     * Saves all the records from this collection to the database one-by-one,
     * inserting or updating as needed.
     * 
     * @return void
     * 
     */
    public function save()
    {
        // reset the "invalid record offset"
        $this->invalid_offsets = array();
        
        // save, instantiating each record
        foreach ($this as $offset => $record) {
            if (! $record->isDeleted()) {
                $result = $record->save();
                // var_dump($record);
                if (! $result) {
                    $this->invalid_offsets[] = $offset;
                }
            }
        }
        
        // done!
        if ($this->invalid_offsets) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 
     * Are there any records in the collection?
     * 
     * @return bool True if empty, false if not.
     * 
     */
    public function isEmpty()
    {
        return empty($this->data);
    }
    
    /**
     * 
     * Are there any invalid records in the collection?
     * 
     * @return bool
     * 
     */
    public function isInvalid()
    {
        if ($this->invalid_offsets) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * Returns an array of invalidation messages from each invalid record, 
     * keyed on the record offset within the collection.
     * 
     * @return array
     * 
     */
    public function getInvalid()
    {
        $invalid = array();
        $list = $this->getInvalidRecords();
        foreach ($list as $offset => $record) {
            $list[$offset] = $record->getInvalid();
        }
        return $list;
    }
    
    /**
     * 
     * Returns an array of the invalid record objects within the collection,
     * keyed on the record offset within the collection.
     * 
     * @return array
     * 
     */
    public function getInvalidRecords()
    {
        $list = array();
        foreach ($this->invalid_offsets as $key) {
            $list[$key] = $this->__get($key);
        }
        return $list;
    }
    
    /**
     * 
     * Deletes each record in the collection one-by-one.
     * 
     * @return void
     * 
     */
    public function deleteAll()
    {
        foreach ($this->data as $key => $val) {
            $this->deleteOne($key);
        }
    }
    
    /**
     * 
     * Fetches a new record and appends it to the collection.
     * 
     * @param array $spec An array of data for the new record.
     * 
     * @return Bull_Model_Record The newly-appended record.
     * 
     */
    public function appendNew(array $data = array())
    {
        // create a new record from the spec and append it
        $record = $this->model->newRecord($data);
        $this->data[] = $record;
        return $record;
    }
    
    /**
     * 
     * Deletes a record from the database and removes it from the collection.
     * 
     * @param mixed $spec If a Bull_Model_Record, looks up the record in
     * the collection and deletes it.  Otherwise, is treated as an offset 
     * value (**not** a record primary key value) and that record is deleted.
     * 
     * @return void
     * 
     * @see getRecordOffset()
     * 
     */
    public function deleteOne($spec)
    {
        if ($spec instanceof Bull_Model_Record) {
            $key = $this->getRecordOffset($spec);
            if ($key === false) {
                throw new Bull_Model_Exception('ERR_NOT_IN_COLLECTION');
            }
        } else {
            $key = $spec;
        }
        
        if ($this->__isset($key)) {
            $record = $this->__get($key);
            if (! $record->isDeleted()) {
                $record->delete();
            }
            $record->free();
            unset($record);
            unset($this->data[$key]);
        }
    }
    
    /**
     * 
     * Removes all records from the collection but **does not** delete them
     * from the database.
     * 
     * @return void
     * 
     */
    public function removeAll()
    {
        $this->data = array();
    }
    
    /**
     * 
     * Removes one record from the collection but **does not** delete it from
     * the database.
     * 
     * @param mixed $spec If a Bull_Model_Record, looks up the record in
     * the collection and deletes it.  Otherwise, is treated as an offset 
     * value (**not** a record primary key value) and that record is removed.
     * 
     * @return void
     * 
     * @see getRecordOffset()
     * 
     */
    public function removeOne($spec)
    {
        if ($spec instanceof Bull_Model_Record) {
            $key = $this->getRecordOffset($spec);
            if ($key === false) {
                
                throw new Bull_Model_Exception('ERR_NOT_IN_COLLECTION');
            }
        } else {
            $key = $spec;
        }
        
        unset($this->data[$key]);
    }
    
    /**
     * 
     * Given a record object, looks up its offset value in the collection.
     * 
     * For this to work, the record primary key must exist in the collection,
     * **and** the record looked up in the collection must have the same
     * primary key and be of the same class.
     * 
     * Note that the returned offset may be zero, indicating the first element
     * in the collection.  As such, you should check the return for boolean 
     * false to indicate failure.
     * 
     * @param Bull_Model_Record $record The record to find in the
     * collection.
     * 
     * @return mixed The record offset (which may be zero), or boolean false
     * if the same record was not found in the collection.
     * 
     */
    public function getRecordOffset($record)
    {
        // the primary value of the record
        $val = $record->getPrimaryVal();
        
        // mapping of primary-key values to offset values
        $map = array_flip($this->getPrimaryVals());
        
        // does the record primary value exist in the collection?
        // use array_key_exists() instead of empty() so we can honor zeroes.
        if (! array_key_exists($val, $map)) {
            return false;
        }
        
        // retain the offset value
        $offset = $map[$val];
        
        // look up the record inside the collection
        $lookup = $this->__get($offset);
        
        // the primary keys are already known to be the same from above.
        // if the classes match as well, consider records to be "the same".
        if (get_class($lookup) === get_class($record)) {
            return $offset;
        } else {
            return false;
        }
    }


    // -----------------------------------------------------------------
    //
    // 数组访问 ArrayAccess
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * ArrayAccess: set a key value; appends to the array when using []
     * notation.
     * 
     * @param string $key The requested key.
     * 
     * @param string $val The value to set it to.
     * 
     * @return void
     * 
     */
    public function offsetSet($key, $val)
    {
        if ($key === null) {
            $key = $this->count();
            if (! $key) {
                $key = 0;
            }
        }
        
        return $this->__set($key, $val);
    }
        
    /**
     * 
     * ArrayAccess: does the requested key exist?
     * 
     * @param string $key The requested key.
     * 
     * @return bool
     * 
     */
    public function offsetExists($key)
    {
        return $this->__isset($key);
    }
    
    /**
     * 
     * ArrayAccess: get a key value.
     * 
     * @param string $key The requested key.
     * 
     * @return mixed
     * 
     */
    public function offsetGet($key)
    {
        return $this->__get($key);
    }
    
    /**
     * 
     * ArrayAccess: unset a key.
     * 
     * @param string $key The requested key.
     * 
     * @return void
     * 
     */
    public function offsetUnset($key)
    {
        $this->__unset($key);
    }


    // -----------------------------------------------------------------
    //
    // 可数 Coutable
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * Countable: how many keys are there?
     * 
     * @return int
     * 
     */
    public function count()
    {
        return count($this->data);
    }

    
    // -----------------------------------------------------------------
    //
    // 迭代器 Iterator
    //
    // -----------------------------------------------------------------
    
    /**
     * 
     * Returns the struct value for the current iterator position.
     * 
     * @return mixed
     * 
     */
    public function current()
    {
        return $this->__get($this->key());
    }
    
    /**
     * 
     * Returns the current iterator position.
     * 
     * @return mixed
     * 
     */
    public function key()
    {
        return key($this->data);
    }
    
    /**
     * 
     * Moves the iterator to the next position.
     * 
     * @return void
     * 
     */
    public function next()
    {
        $this->valid = (next($this->data) !== false);
    }
    
    /**
     * 
     * Moves the iterator to the first position.
     * 
     * @return void
     * 
     */
    public function rewind()
    {
        $this->valid = (reset($this->data) !== false);
    }
    
    /**
     * 
     * Is the current iterator position valid?
     * 
     * @return void
     * 
     */
    public function valid()
    {
        return $this->valid;
    }
}
