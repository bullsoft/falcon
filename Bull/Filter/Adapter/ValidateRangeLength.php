<?php
/**                                                                                              
 *                                                                                               
 * Validates that a value's length is within a given range.                                      
 *                                                                                               
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                               
 */

class Bull_Filter_Adapter_ValidateRangeLength extends Bull_Filter_Adapter_Abstract
{
    /**                                                                                          
     *                                                                                           
     * Validates that the length of the value is within a given range.                           
     *                                                                                           
     * @param mixed $value The value to validate.                                                
     *                                                                                           
     * @param mixed $min The minimum valid length.                                               
     *                                                                                           
     * @param mixed $max The maximum valid length.                                               
     *                                                                                           
     * @return bool True if valid, false if not.                                                 
     *                                                                                           
     */
    public function __invoke($value, $min, $max)
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        $len = strlen($value);
        return ($len >= $min && $len <= $max);
    }
}
