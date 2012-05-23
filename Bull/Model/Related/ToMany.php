<?php
/**
 * 
 * Represents the characteristics of a "to-many" related model.* 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
abstract class Bull_Model_Related_ToMany extends Bull_Model_Related
{
    /**
     * 
     * Is this related to one record?
     * 
     * @return bool
     * 
     */
    public function isOne()
    {
        return false;
    }
    
    /**
     * 
     * Is this related to many records?
     * 
     * @return bool
     * 
     */
    public function isMany()
    {
        return true;
    }
    
    /**
     * 
     * Returns foreign data as a collection object.
     * 
     * @param array $data The foreign data.
     * 
     * @return Bull_Model_Collection A foreign collection object.
     * 
     */
    public function newObject(array $data)
    {
        return $this->_foreign_model->getCollection($data);
    }
    
    /**
     * 
     * Returns an empty related value for an internal array result.
     * 
     * @return null
     * 
     */
    public function getEmpty()
    {
        return array();
    }
    
    /**
     * 
     * Fetches a new related collection.
     * 
     * @param array $data Data for the new collection.
     * 
     * @return Solar_Sql_Model_Collection
     * 
     */
    public function fetchNew(array $data)
    {
        return $this->_foreign_model->newCollection($data);
    }
    

    public function fetch(array $data)
    {
        $related = $this;
        return function () use ($related, $data) {
            return $related->getModel()->selectAll(
                $related->cols,
                array($related->foreign_col."='{$data[$related->native_col]}'"));
        };
    }
    /**
     * 
     * Sets the base name for the foreign class; assumes the related name is
     * is already plural.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function setForeignClass($opts)
    {
        if (empty($opts['foreign_class'])) {
            $this->foreign_class = "Framework_Model_"
                . $this->_inflect->underToStudly($this->_native_model->name)
                . "_" . $this->_inflect->underToStudly($opts['name']);
        } else {
            $this->foreign_class = $opts['foreign_class'];
        }
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
            $collated[$val][] = $row;
        }
        return $collated;
    }
    
}
