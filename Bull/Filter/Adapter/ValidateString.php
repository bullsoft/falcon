<?php
/**                                                                              
 *                                                                               
 * Validates that a value can be represented as a string.                        
 *                                                                               
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                               
 */

class Bull_Filter_Adapter_ValidateString extends Bull_Filter_Adapter_Abstract
{
    /**                                                                          
     *                                                                           
     * Validates that the value can be represented as a string.                  
     *                                                                           
     * Essentially, this means any scalar value is valid (no arrays, objects,    
     * resources, etc).                                                          
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

        return is_scalar($value);
    }
}
