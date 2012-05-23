<?php
/**
 * 
 * Abstract class to represent the characteristics of a related model.
 * 
 * @package Bull.Model
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
abstract class Bull_Model_Related
{
    /**
     * 
     * The name of the relationship as defined by the original (native) model.
     * 
     * @var string
     * 
     */
    public $name;
    
    /**
     * 
     * The type of the relationship as defined by the original (native) model;
     * e.g., 'has_one', 'belongs_to', 'has_many'.
     * 
     * @var string
     * 
     */
    public $type;
    
    /**
     * 
     * The class of the native model.
     * 
     * @var string
     * 
     */
    public $native_class;
    
    /**
     * 
     * The alias for the native table.
     * 
     * @var string
     * 
     */
    public $native_table;
    
    /**
     * 
     * The native column to match against the foreign primary column.
     * 
     * @var string
     * 
     */
    public $native_col;
    
    /**
     * 
     * The class name of the foreign model. Default is the first
     * matching class for the relationship name, as loaded from the parent
     * class stack.
     * 
     * 
     * @var string
     * 
     */
    public $foreign_class;
    
    /**
     * 
     * The name of the table for the foreign model. Default is the
     * table specified by the foreign model.
     * 
     * @var string
     * 
     */
    public $foreign_table;
    
    /**
     * 
     * The name of the column to join with in the *foreign* table.
     * This forms one-half of the relationship.  Default is per association
     * type.
     * 
     * @var string
     * 
     */
    public $foreign_col;
    
    /**
     * 
     * The name of the foreign primary column.
     * 
     * @var string
     * 
     */
    public $foreign_primary_col;
    
    /**
     * 
     * Additional conditions when fetching related records.
     * 
     * @var string|array
     * 
     */
    public $conditions;
    
    /**
     * 
     * Additional ORDER clauses when fetching related records.
     * 
     * @var string|array
     * 
     */
    public $order;
    
    /**
     * 
     * An instance of the native (origin) model that defined this relationship.
     * 
     * @var Bull_Model_Abstract
     * 
     */
    protected $_native_model;
    
    /**
     * 
     * An instance of the foreign (related) model.
     * 
     * @var Bull_Model_Abstract
     * 
     */
    protected $_foreign_model;
    
    /**
     *
     * Name inflect.
     *
     * @var Bull_Util_Inflect
     *
     */
    protected $_inflect;
    
    /**
     * 
     * Sets the native (origin) model instance.
     * 
     * @param Bull_Model $model The native model instance.
     * 
     * @return void
     * 
     */
    public function setNativeModel($model)
    {
        $this->_native_model = $model;
        $this->native_class  = get_class($model);
        $this->native_table  = $this->_native_model->table;
    }
    
    /**
     * 
     * Returns the related (foreign) model instance.
     * 
     * @return Bull_Model_Abstract
     * 
     */
    public function getModel()
    {
        return $this->_foreign_model;
    }
    
    /**
     * 
     * Loads this relationship object with user-defined characteristics
     * (options), and corrects them as needed.
     * 
     * @param array $opts The user-defined options for the relationship.
     * 
     * @return void
     * 
     */
    public function load($opts)
    {
        $this->name = $opts['name'];
        $this->_inflect = new Bull_Util_Inflect();
        $this->setType();
        $this->setForeignClass($opts);
        $this->setForeignModel($opts);
        $this->setCols($opts);
        $this->setConditions($opts);
        $this->setOrder($opts);
        $this->setRelated($opts);
    }
    
    /**
     * 
     * Is this related to one record?
     * 
     * @return bool
     * 
     */
    abstract public function isOne();
    
    /**
     * 
     * Is this related to many records?
     * 
     * @return bool
     * 
     */
    abstract public function isMany();
    
    /**
     * 
     * Packages foreign data as a record or collection object.
     * 
     * @param array $data The foreign Data
     * 
     * @return Bull_Model_Record|Bull_Model_Collection A record or 
     * collection object.
     * 
     */
    abstract public function newObject(array $data);
    
    /**
     * 
     * Fetches a new record or collection object.
     * 
     * @param array $data The data for the record or collection.
     * 
     * @return Bull_Model_Record|Bull_Model_Collection A record or 
     * collection object.
     * 
     */
    abstract public function fetchNew(array $data);
    
    /**
     * 
     * Returns a new empty value appropriate for a lazy- or eager-fetch;
     * this is different for each kind of related.
     * 
     * @return mixed
     * 
     */
    abstract public function fetchEmpty();

    
    abstract public function fetch(array $data);
    /**
     * 
     * Sets the base name for the foreign class.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    abstract protected function setForeignClass($opts);
    
    /**
     * 
     * Sets the foreign model instance based on user-defined relationship
     * options.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function setForeignModel($opts)
    {
        // get the foreign model from the catalog by its class name
        $this->_foreign_model = Bull_Di_Container::newInstance($this->foreign_class);
        
        // get its table name
        $this->foreign_table = $this->_foreign_model->table;
        
        // and its primary column
        $this->foreign_primary_col = $this->_foreign_model->primary();
    }
    
    /**
     * 
     * Sets the foreign columns to be selected based on user-defined 
     * relationship options.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function setCols($opts)
    {
        // the list of foreign table cols to retrieve
        if (empty($opts['cols'])) {
            $this->cols = array_keys($this->_foreign_model->columns());
        } elseif (is_string($opts['cols'])) {
            $this->cols = explode(',', $opts['cols']);
        } else {
            $this->cols = (array) $opts['cols'];
        }
        
        // make sure we always retrieve the foreign primary key value,
        // if there is one.
        $primary = $this->_foreign_model->primary();
        if ($primary && ! in_array($primary, $this->cols)) {
            $this->cols[] = $primary;
        }
    }
    
    /**
     * 
     * Sets additional conditions from the relationship definition; these are
     * used in the WHERE and/or JOIN ON conditions.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function setConditions($opts)
    {
        if (empty($opts['conditions'])) {
            $this->conditions = array();
        } else {
            $this->conditions = (array) $opts['conditions'];
        }
    }
    
    /**
     * 
     * Sets default ORDER clause from the relationship definition.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function setOrder($opts)
    {
        if (empty($opts['order'])) {
            $this->order = array();
        } else {
            $this->order = (array) $opts['order'];
        }
    }
    
    /**
     * 
     * Sets the relationship type.
     * 
     * @return void
     * 
     */
    abstract protected function setType();
    
    /**
     * 
     * Sets the characteristics for the related model, table, etc. based on
     * the user-defined relationship options.
     * 
     * @param array $opts The user-defined options for the relationship.
     * 
     * @return void
     * 
     */
    abstract protected function setRelated($opts);
    
    /**
     * 
     * Given a results array, collates the results based on a key within each
     * result.
     * 
     * @param array $array The results array.
     * 
     * @param string $key The key to collate by.
     * 
     * @return array The collated array.
     * 
     */
    abstract protected function collate($array, $key);
    
    /**
     * 
     * Pre-save hook for saving related records or collections from a native
     * record.
     * At least for now, only belongs-to needs this.
     
     * @param Solar_Sql_Model_Record $native The native record to save from.
     * 
     * @return void
     * 
     */
    public function preSave($native, array $data) {}
    
    /**
     * 
     * Saves a related record or collection from a native record.
     * 
     * @param Bull_Model_Record $native The native record to save from.
     * 
     * @return void
     * 
     */
    abstract public function save($native, array $data);
    
    /**
     * 
     * Is the related record or collection valid?
     * 
     * @param Bull_Model_Record $native The native record to check from.
     * 
     * @return bool
     * 
     */
    public function isInvalid(array $data)
    {
        if (!empty($data)) {
            return $this->fetchNew($data)->isInvalid();
        } else {
            // if it's not there, it can't be invalid
            return false;
        }
    }
}
