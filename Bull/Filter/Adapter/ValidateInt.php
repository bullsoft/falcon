<?php
/**                                                                             
 *                                                                              
 * Validates that a value represents an integer.                                
 *                                                                              
 * @package Bull.Filter.Adapter      
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                              
 */

class Bull_Filter_Adapter_ValidateInt extends Bull_Filter_Adapter_Abstract
{
    /**                                                                         
     *                                                                          
     * Validates that the value represents an integer.                          
     *                                                                          
     * @param mixed $value The value to validate.                               
     *                                                                          
     * @return bool True if valid, false if not.                                
     *                                                                          
     */
    public function __invoke($value)
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        if (is_int($value)) {
            return true;
        }
        
        // otherwise, must be numeric, and must be same as when cast to int     
        return is_numeric($value) && $value == (int) $value;
    }
}
