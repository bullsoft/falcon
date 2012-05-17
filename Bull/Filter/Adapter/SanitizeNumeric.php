<?php
/**
 * 
 * Sanitizes a value to a string with only numeric characters.
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeNumeric extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Strips non-numeric characters from the value.
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
        return (string) $this->objManager->sanitizeFloat($value);
    }
}