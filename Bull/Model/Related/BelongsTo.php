<?php
/**
 * 
 * Represents the characteristics of a relationship where a native model
 * "belongs to" a foreign model.
 *
 * @package Bull.Model
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 */
class Bull_Model_Related_BelongsTo extends Bull_Model_Related_ToOne
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
        $this->type = 'belongs_to';
    }
    
    /**
     * 
     * A support method for _fixRelated() to handle belongs-to relationships.
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
            // named by foreign primary key
            $this->foreign_col = $this->_foreign_model->primary();
        } else {
            $this->foreign_col = $opts['foreign_col'];
        }
        
        // the native column
        if (empty($opts['native_col'])) {
            // named by foreign table's suggested foreign_col name
            $this->native_col = $this->_foreign_model->foreign_col;
        } else {
            $this->native_col = $opts['native_col'];
        }
    }
    
    /**
     * 
     * Returns a null when there is no related data.
     * 
     * @return null
     * 
     */
    public function fetchEmpty()
    {
        return null;
    }

    /**
     * 
     * Pre-save behavior when saving foreign records through this 
     * relationship.
     * 
     * In a "belongs-to", the foreign value is stored in the native column,
     * whereas in "has", the native value is stored in the foreign column.
     * 
     * @param Bull_Model_Record $native The native record that is trying
     * to save a foreign record through this relationship.
     * 
     * @return void
     * 
     */
    public function preSave($native, array $data)
    {
        // see if we have the foreign record that the native record belongs to
        // $foreign = $native->{$this->name};
        if (empty($data)) {
            // we need the record the native belongs to, to connect the two
            throw new Bull_Model_Exception('ERR_NO_RELATED_RECORD');
        } else {
            // the foreign record exists, connect with the native
            $native->{$this->native_col} = $data[$this->foreign_col];
        }
    }
    
    /**
     * 
     * Save a foreign records through this relationship; the belongs-to
     * relationship *does not* save the belonged-to record, to avoid
     * recursion issues.
     * 
     * @param Bull_Model_Record $native The native record that is trying
     * to save a foreign record through this relationship.
     * 
     * @return void
     * 
     */
    public function save($native, array $data) {}
}