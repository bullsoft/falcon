<?php
/**
 * 
 * Forces a value to a string, no encoding or escaping.
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeString extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Forces the value to a string.
     * 
     * @param mixed $value The value to be sanitized.
     * 
     * @return string The sanitized value.
     * 
     */
    public function __invoke($value)
    {
        // if the value is not required, and is blank, sanitize to null
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);
                
        if ($null) {
            return null;
        }
        
        // normal sanitize
        return (string) $value;
    }
}