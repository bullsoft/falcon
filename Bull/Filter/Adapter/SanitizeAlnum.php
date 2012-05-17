<?php
/**
 * 
 * Sanitizes a value to a string with only alphanumeric characters.
 *
 * @package Bull.Filter.Adapter
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 */
class Bull_Filter_Adapter_SanitizeAlnum extends Bull_Filter_Adapter_Abstract
{
    public function __invoke($value)
    {
        // if the value is not required, and is blank, sanitize to null        
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);                         

        if ($null) {
            return null;
        }

        // normal sanitize                                                     
        return preg_replace('/[^a-z0-9]/i', '', $value);
    }

}