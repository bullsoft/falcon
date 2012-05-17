<?php
/**
 * 
 * Sanitizes a value to a string using str_replace().
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeStrReplace extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Applies [[php::str_replace() | ]] to the value.
     * 
     * @param mixed $value The value to be sanitized.
     * 
     * @param string|array $search Find this string.
     * 
     * @param string|array $replace Replace with this string.
     * 
     * @return string The sanitized value.
     * 
     */
    public function __invoke($value, $search, $replace)
    {
        // if the value is not required, and is blank, sanitize to null
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);
                
        if ($null) {
            return null;
        }
        
        // normal sanitize
        return str_replace($search, $replace, $value);
    }
}