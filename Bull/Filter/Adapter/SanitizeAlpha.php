<?php
/**
 * 
 * Sanitizes a value to a string with only alphabetic characters.
 * 
 * @package Bull.Filter.Adatper
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 *
 */
class Bull_Filter_Adapter_SanitizeAlpha extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Strips non-alphabetic characters from the value.
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
        return preg_replace('/[^a-z]/i', '', $value);
    }
}