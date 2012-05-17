<?php
/**                                                                                             
 *                                                                                              
 * Validates that a value is of a certain ctype.                                                
 *                                                                                              
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                              
 */

class Bull_Filter_Adapter_ValidateCtype extends Bull_Filter_Adapter_Abstract
{
    /**                                                                                         
     *                                                                                          
     * Validates the value against a [[php::ctype | ]] function.                                
     *                                                                                          
     * @param mixed $value The value to validate.                                               
     *                                                                                          
     * @param string $type The ctype to validate against: 'alnum',                              
     * 'alpha', 'digit', etc.                                                                   
     *                                                                                          
     * @return bool True if the value matches the ctype, false if not.                          
     *                                                                                          
     */
    public function __invoke($value, $type)                                                
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        $func = 'ctype_' . $type;
        
        return (bool) $func((string)$value);
    }
}
