<?php
/**                                                                                         
 *                                                                                          
 * Validates that a value is numeric
 *                                                                                          
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *                                                                                          
 */

class Bull_Filter_Adapter_ValidateNumeric extends Bull_Filter_Adapter_Abstract
{
    public function __invoke($value)
    {
        if ($this->objManager->validateBlank($value)) {
            return ! $this->objManager->getRequire();
        }

        return is_numeric($value);
    }

}