<?php
/**                                                                               
 *                                                                                
 * Validates that the value is letters only (upper or lower case).                
 *                                                                                
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                
 */
class Bull_Filter_Adapter_ValidateAlpha extends Bull_Filter_Adapter_Abstract
{
    /**                                                                           
     *                                                                            
     * Validates that the value is letters only (upper or lower case).            
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
        return ctype_alpha($value);
    }
}
