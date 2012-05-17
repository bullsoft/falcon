<?php
/**                                                                                         
 *                                                                                          
 * Validates that a value is within a given range.
 *                                                                                          
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                          
 */

class Bull_Filter_Adapter_ValidateRange extends Bull_Filter_Adapter_Abstract
{
    public function __invoke($value, $min, $max)
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        return ($value >= $min && $value <= $max);
    }
}