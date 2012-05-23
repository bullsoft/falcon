<?php
/**
 * 
 * Represents the characteristics of a relationship where a native model
 * "has one" of a foreign model.
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Model_Related_HasOne extends Bull_Model_Related_ToOne
{
    /**
     * 
     * Sets the relationship type.
     * 
     * @return void
     * 
     */
    protected function setType()
    {
        $this->type = 'has_one';
    }
    
    /**
     * 
     * A support method for _fixRelated() to handle has-one relationships.
     * 
     * @param array &$opts The relationship options; these are modified in-
     * place.
     * 
     * @return void
     * 
     */
    protected function setRelated($opts)
    {
        // the foreign column
        if (empty($opts['foreign_col'])) {
            // named by native table's suggested foreign_col name
            $this->foreign_col = $this->_native_model->foreign_col;
        } else {
            $this->foreign_col = $opts['foreign_col'];
        }
        
        // the native column
        if (empty($opts['native_col'])) {
            // named by native primary key
            $this->native_col = $this->_native_model->primary();
        } else {
            $this->native_col = $opts['native_col'];
        }
    }
    
    /**
     * 
     * Returns a new record when there is no related data.
     * 
     * @return null
     * 
     */
    public function fetchEmpty()
    {
        return $this->fetchNew();
    }
    
    /**
     * 
     * Saves a related record from a native record.
     * 
     * @param Bull_Model_Record $native The native record to save from.
     * 
     * @return void
     * 
     */
    public function save($native, array $data)
    {
        // cover for has-one-or-null
        if (empty($data)) {
            return;
        }
        // set the foreign_col to the native value
        $data[$this->foreign_col] = $native->{$this->native_col};
        $record = $this->fetchNew($data)->save();
    }
}
