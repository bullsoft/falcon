<?php                                                                    
/**                                                                      
 *                                                                       
 * Validates that this value is equal to some other element in the filter
 * chain (note that equality is not strict, so type does not matter).    
 *                                                                       
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                       
 */

class Bull_Filter_Adapter_ValidateEquals extends Bull_Filter_Adapter_Abstract
{
    /**                                                                        
     *                                                                         
     * Validates that this value is equal to some other element in the filter  
     * chain (note that equality is *not* strict, so type does not matter).    
     *                                                                         
     * If the other element does not exist in $this->_data, the validation     
     * will fail.                                                              
     *                                                                         
     * @param mixed $value The value to validate.                              
     *                                                                         
     * @param string $other_key Check against the value of this element in     
     * $this->_data.                                                           
     *                                                                         
     * @return bool True if the values are equal, false if not equal.          
     *                                                                         
     */
    public function __invoke($value, $other_key)
    {
        if (! $this->objManager->dataKeyExists($other_key)) {
            return false;
        }

        $other = $this->objManager->getData($other_key);
        return $value == $other;
    }
}
