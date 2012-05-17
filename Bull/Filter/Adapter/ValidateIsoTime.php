<?php
/**                                                                                               
 *                                                                                                
 * Validates that a value is an ISO 8601 time string.                                             
 *                                                                                                
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                                
 */

class Bull_Filter_Adapter_ValidateIsoTime extends Bull_Filter_Adapter_ValidateIsoTimestamp
{
    /**                                                                                           
     *                                                                                            
     * Validates that the value is an ISO 8601 time string (hh:ii::ss format).                    
     *                                                                                            
     * As an alternative, the value may be an array with all of the keys for                      
     * `H`, `i`, and optionally `s`, in which case the value is                                   
     * converted to an ISO 8601 string before validating it.                                      
     *                                                                                            
     * Per note from Chris Drozdowski about ISO 8601, allows two                                  
     * midnight times ... 00:00:00 for the beginning of the day, and                              
     * 24:00:00 for the end of the day.                                                           
     *                                                                                            
     * @param mixed $value The value to validate.                                                 
     *                                                                                            
     * @return bool True if valid, false if not.                                                  
     *                                                                                            
     */
    public function __invoke($value)
    {
        // look for His keys?                                                                     
        if (is_array($value)) {
            $value = $this->_arrayToTime($value);
        }

        $expr = '/^(([0-1][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9]$/D';

        return $this->objManager->validatePregMatch($value, $expr) ||
            ($value == '24:00:00');
    }
}
