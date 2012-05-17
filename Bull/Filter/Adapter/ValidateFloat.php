<?php
/**                                                                                                  
 *                                                                                                   
 * Validates that a value represents a float.                                                        
 *                                                                                                   
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                                   
 */

class Bull_Filter_Adapter_ValidateFloat extends Bull_Filter_Adapter_Abstract
{
    /**                                                                                              
     *                                                                                               
     * Validates that the value represents a float.                                                  
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

        if (is_float($value)) {
            return true;
        }

        // otherwise, must be numeric, and must be same as when cast to float                        
        return is_numeric($value) && $value == (float) $value;
    }
}
