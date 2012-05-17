<?php
/**                                                                                                
 *                                                                                                 
 * Validates that the value is only letters (upper or lower case) and digits.                      
 *                                                                                                 
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                                 
 */

class Bull_Filter_Adapter_ValidateAlnum extends Bull_Filter_Adapter_Abstract
{
    /**                                                                                            
     *                                                                                             
     * Validates that the value is only letters (upper or lower case) and digits.                  
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

        return ctype_alnum((string)$value);
    }
}
