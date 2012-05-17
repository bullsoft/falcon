<?php
/**                                                                                           
 *                                                                                            
 * Validates that a value is a legal IPv4 address.                                            
 *                                                                                            
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                            
 */

class Bull_Filter_Adapter_ValidateIpv4 extends Bull_Filter_Adapter_Abstract
{                                                                                             
    /**                                                                                       
     *                                                                                        
     * Validates that the value is a legal IPv4 address.                                      
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

        // does the value convert back and forth properly?                                    
        $result = ip2long($value);
        if ($result == -1 || $result === false) {
            // does not properly convert to a "long" result                                   
            return false;
        } elseif (long2ip($result) !== $value) {
            // the long result does not convert back to an identical original                 
            // value                                                                          
            return false;
        } else {
            // looks valid                                                                    
            return true;
        }
    }
}
