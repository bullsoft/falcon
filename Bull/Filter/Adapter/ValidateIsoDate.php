<?php                                                 
/**                                                   
 *                                                    
 * Validates that a value is an ISO 8601 date string. 
 *                                                    
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                    
 */

class Bull_Filter_Adapter_ValidateIsoDate extends Bull_Filter_Adapter_ValidateIsoTimestamp
{
    /**                                                                        
     *                                                                         
     * Validates that the value is an ISO 8601 date string.                    
     *                                                                         
     * The format is "yyyy-mm-dd".  Also checks to see that the date           
     * itself is valid (for example, no Feb 30).                               
     *                                                                         
     * @param mixed $value The value to validate.                              
     *                                                                         
     * @return bool True if valid, false if not.                               
     *                                                                         
     */
    public function __invoke($value)
    {
        // look for Ymd keys?                                                  
        if (is_array($value)) {
            $value = $this->_arrayToDate($value);
        }

        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        // basic date format                                                   
        // yyyy-mm-dd                                                          
        $expr = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/D';

        // validate                                                            
        if (preg_match($expr, $value, $match) &&
            checkdate($match[2], $match[3], $match[1])) {
            return true;
        } else {
            return false;
        }
    }
}
