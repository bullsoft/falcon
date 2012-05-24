<?php
/**
 * 
 * Represents the characteristics of a relationship where a native model
 * "has many" of a foreign model.
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Model_Related_HasMany extends Bull_Model_Related_ToMany
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
        $this->type = 'has_many';
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
     * Saves a related collection from a native record.
     * 
     * @param Bull_Model_Record $native The native record to save from.
     * 
     * @return void
     * 
     */
    public function save($native)
    {
        // get the foreign collection to work with
        $foreign = $native->{$this->name};
        
        if ($foreign->isEmpty()) {
            return;
        }
        
        // set the foreign_col on each foreign record to the native value
        foreach ($foreign as $key => $record) {
            $record->{$this->foreign_col} = $native->{$this->native_col};
        }
        $foreign->save();
    }
}
