<?php
/**
 * 
 * Sanitizes a value to boolean true or false.
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeBool extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * String representations of "true" boolean values.
     * 
     * @var array
     * 
     */
    protected $_true = array('1', 'on', 'true', 't', 'yes', 'y');
    
    /**
     * 
     * String representations of "false" boolean values.
     * 
     * @var array
     * 
     */
    protected $_false = array('0', 'off', 'false', 'f', 'no', 'n', '');
    
    /**
     * 
     * Forces the value to a boolean.
     * 
     * Note that this recognizes $this->_true and $this->_false values.
     * 
     * @param mixed $value The value to sanitize.
     * 
     * @return bool The sanitized value.
     * 
     */
    public function __invoke($value)
    {
        // if the value is not required, and is blank, sanitize to null
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);
                
        if ($null) {
            return null;
        }
        
        // PHP booleans
        if ($value === true || $value === false) {
            return $value;
        }
        
        // "string" booleans
        $value = strtolower(trim($value));
        if (in_array($value, $this->_true)) {
            return true;
        }
        if (in_array($value, $this->_false)) {
            return false;
        }
        
        // forcibly recast to a boolean
        return (bool) $value;
    }
}