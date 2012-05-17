<?php
/**                                                                               
 *                                                                                
 * Validates that a value matches a regular expression.                           
 *                                                                                
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                
 */
class Bull_Filter_Adapter_ValidatePregMatch extends Bull_Filter_Adapter_Abstract
{
    /**                                                                           
     *                                                                            
     * Validates the value against a regular expression.                          
     *                                                                            
     * Uses [[php::preg_match() | ]] to compare the value against the given       
     * regular epxression.                                                        
     *                                                                            
     * @param mixed $value The value to validate.                                 
     *                                                                            
     * @param string $expr The regular expression to validate against.            
     *                                                                            
     * @return bool True if the value matches the expression, false if not.       
     *                                                                            
     */
    public function __invoke($value, $expr)
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        return (bool) preg_match($expr, $value);
    }
}
