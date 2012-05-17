<?php
/**                                                                                         
 *                                                                                          
 * Validates that a value is not blank
 *                                                                                          
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                          
 */

class Bull_Filter_Adapter_ValidateNotBlank extends Bull_Filter_Adapter_Abstract
{
    public function __invoke($value)
    {
        if (is_bool($value) || is_int($value) || is_float($value)) {
            return true;
        }
        return (trim((string)$value) != '');
    }
}
