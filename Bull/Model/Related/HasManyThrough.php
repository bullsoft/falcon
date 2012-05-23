<?php
/**
 * 
 * Represents the characteristics of a relationship where a native model
 * "has many" of a foreign model.  This includes "has many through" (i.e.,
 * a many-to-many relationship through an interceding mapping model).
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 * 
 */
class Bull_Model_Related_HasManyThrough extends Bull_Model_Related_ToMany
{
    /**
     * 
     * The relationship name through which we find foreign records.
     * 
     * @var string
     * 
     */
    public $through;

    /**
     * 
     * In the "through" table, the column that has the matching native value.
     * 
     * @var string
     * 
     */
    public $through_native_col;
    
    /**
     * 
     * In the "through" table, the column that has the matching foreign value.
     * 
     * @var string
     * 
     */
    public $through_foreign_col;
    
    /**
     * 
     * The conditions retrieved from the "through" model.
     * 
     * @var string|array
     * 
     */
    public $through_conditions;


    public $_through_class;
    

    protected $_through_model;
    
    /**
     * 
     * Sets the relationship type.
     * 
     * @return void
     * 
     */
    protected function setType()
    {
        $this->type = 'has_many_through';
    }
    
    /**
     * 
     * A support method for _fixRelated() to handle has-many relationships.
     * 
     * @param array &$opts The relationship options; these are modified in-
     * place.
     * 
     * @return void
     * 
     */
    protected function setRelated($opts)
    {
        // retain the name of the "through" related
        $this->through = $opts['through'];
        $this->_through_class = "Framework_Model_"
            . $this->_inflect->underToStudly($this->_native_model->name)
            . "_" . $this->_inflect->underToStudly($this->through);
        $this->_through_model = Bull_Di_Container::newInstance($this->_through_class);
        
        // the foreign column
        if (empty($opts['foreign_col'])) {
            // named by foreign primary key (e.g., foreign.id)
            $this->foreign_col = $this->_foreign_model->primary();
        } else {
            $this->foreign_col = $opts['foreign_col'];
        }
        
        // the native column
        if (empty($opts['native_col'])) {
            // named by native primary key (e.g., native.id)
            $this->native_col = $this->_native_model->primary();
        } else {
            $this->native_col = $opts['native_col'];
        }
        
        // what's the native model key in the through table?
        if (empty($opts['through_native_col'])) {
            $this->through_native_col = $this->_native_model->foreign_col;
        } else {
            $this->through_native_col = $opts['through_native_col'];
        }
        
        // what's the foreign model key in the through table?
        if (empty($opts['through_foreign_col'])) {
            $this->through_foreign_col = $this->_foreign_model->foreign_col;
        } else {
            $this->through_foreign_col = $opts['through_foreign_col'];
        }
    }
    
    /**
     * 
     * Fetches the related collection for a native ID or record.
     * 
     * @param mixed $spec If a scalar, treated as the native primary key
     * value; if an array or record, retrieves the native primary key value
     * from it.
     * 
     * @return object The related collection object.
     * 
     */
    public function fetch(array $spec)
    {
        if (is_array($spec)) {
            $native_id = $spec[$this->native_col];
        } else {
            $native_id = $spec;
        }

        $through = $this->_through_model
            ->selectAll(array($this->through_native_col, $this->through_foreign_col),
                        array($this->through_native_col ." = ?"=> $native_id));
        
        $foreign_ids = array();
        foreach($through as $val)
        {
            $foreign_ids[] = $val[$this->through_foreign_col];
        }

        $foreign = $this->_foreign_model
            ->selectAll($this->cols,
                        array($this->foreign_col . " IN ( ? )" => implode(',', $foreign_ids)));
        
        return $this->_foreign_model->getCollection($foreign);
    }
    
    /**
     * 
     * Collates a result array by an array key, grouping the results by that
     * value.
     *
     * @param array $array The result array.
     *
     * @param string $key The key in the array to collate by.
     * 
     * @return array An array of collated elements, keyed by the collation 
     * value.
     * 
     */
    protected function collate($array, $key)
    {
        $collated = array();
        foreach ($array as $i => $row) {
            $val = $row[$key];
            unset($row[$key]); // clear the key from the array
            $collated[$val][] = $row;
        }
        return $collated;
    }
    
    /**
     * 
     * Returns a new, empty collection when there is no related data.
     * 
     * @return Bull_Model_Collection
     * 
     */
    public function fetchEmpty()
    {
        return $this->fetchNew();
    }
    
    /**
     * 
     * Saves the related "through" collection *and* the foreign collection
     * from a native record.
     * 
     * Ensures the "through" collection has an entry for each foreign record,
     * and adds/removes entried in the "through" collection as needed.
     * 
     * @param Bull_Model_Record $native The native record to save from.
     * 
     * @return void
     * 
     */
    public function save($native, array $data)
    {
        // if no foreign records, kill off all through records
        if (empty($data)) {
            return;
        }

        // get the foreign collection to work with
        $foreign = $this->fetchNew($data);
        
        // get the through collection to work with
        // $through = $native->{$this->through};
        
        // save the foreign records as they are, which creates the necessary
        // primary key values the through mapping will need
        $foreign->save();
        
        // the list of existing foreign values
        $foreign_list = $foreign->getColVals($this->foreign_col);

        $through = $this->_through_model->newCollection();
        
        // find mappings that *don't* exist, and add them
        foreach ($foreign_list as $foreign_val) {
                $through->appendNew(array(
                    $this->through_native_col  => $native->{$this->native_col},
                    $this->through_foreign_col => $foreign_val,
                ));
        }
        
        // done with the mappings, save them
        $through->save();
    }
    
    /**
     * 
     * Are the related "foreign" and "through" collections valid?
     * 
     * @param Solar_Sql_Model_Record $native The native record.
     * 
     * @return bool
     * 
     */
    public function isInvalid($native)
    {
        $foreign = $native->{$this->name};
        $through = $native->{$this->through};
        
        // no foreign and no through means they can't be invalid
        if (! $foreign && ! $through) {
            return false;
        }
        
        // is foreign invalid?
        if ($foreign && $foreign->isInvalid()) {
            return true;
        }
        
        // is through invalid?
        if ($through && $through->isInvalid()) {
            return true;
        }
        
        // both foreign and through are valid
        return false;
    }
}
