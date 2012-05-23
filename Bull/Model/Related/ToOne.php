<?php
/**
 * 
 * Represents the characteristics of a "to-one" related model.
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
abstract class Bull_Model_Related_ToOne extends Bull_Model_Related
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
        return true;
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
        return false;
    }
    
    /**
     * 
     * Returns foreign data as a record object.
     * 
     * @param array $data The foreign data.
     * 
     * @return Bull_Model_Record A foreign record object.
     * 
     */
    public function newObject(array $data)
    {
        return $this->_foreign_model->getRecord($data);
    }

    /**
     * 
     * Fetches a new related record.
     * 
     * @param array $data Data for the new record.
     * 
     * @return Bull_Model_Record
     * 
     */
    public function fetchNew(array $data)
    {
        return $this->_foreign_model->newRecord($data);
    }

    /**
     *
     * Fetch Data according to native record.
     *
     * @param array $data Data from the native record.
     *
     */
    public function fetch(array $data)
    {
        $related = $this;
        return function () use ($related, $data) {
            return $related->getModel()->selectOne(
                $related->cols,
                array($related->foreign_col."='{$data[$related->native_col]}'"));
        };
    }
    
    /**
     * 
     * Sets the base name for the foreign class; assumes the related name is
     * is singular and inflects it to plural.
     * 
     * @param array $opts The user-defined relationship eager.
     * 
     * @return void
     * 
     */
    protected function setForeignClass($opts)
    {
        if (empty($opts['foreign_class'])) {
            // ... then use the plural form of the name to get the class.
            $this->foreign_class = "Framework_Model_"
                . $this->_inflect->underToStudly($this->_native_model->name)
                . "_" . $this->_inflect->underToStudly($opts['name']);
        } else {
            $this->foreign_class = $opts['foreign_class'];
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
            $collated[$val] = $row;
        }
        return $collated;
    }
}
