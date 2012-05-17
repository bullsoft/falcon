<?php
/**                                                                                                  
 *                                                                                                   
 * Validates that a value is in a list of allowed values.                                            
 *                                                                                                   
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                                   
 */

class Bull_Filter_Adapter_ValidateInList extends Bull_Filter_Adapter_Abstract
{
    /**                                                                                              
     *                                                                                               
     * Validates that the value is in a list of allowed values.                                      
     *                                                                                               
     * Strict checking is enforced, so a string "1" is not the same as                               
     * an integer 1.  This helps to avoid matching 0 and empty, etc.                                 
     *                                                                                               
     * @param mixed $value The value to validate.                                                    
     *                                                                                               
     * @param array $array An array of allowed values.                                               
     *                                                                                               
     * @return bool True if valid, false if not.                                                     
     *                                                                                               
     */
    public function __invoke($value, $array)                                                
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        return in_array($value, (array) $array, true);
    }
}
