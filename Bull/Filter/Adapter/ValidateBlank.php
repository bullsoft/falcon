<?php
/**                                                                               
 *                                                                                
 * Validates that the value is blank or not
 *                                                                                
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                
 */

class Bull_Filter_Adapter_ValidateBlank extends Bull_Filter_Adapter_Abstract
{
    public function __invoke($value)
    {
        if (! is_string($value) && ! is_null($value)) {
            return false;
        }

        return trim($value) == '';                       
    }
}