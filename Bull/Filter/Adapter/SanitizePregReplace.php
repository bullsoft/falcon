<?php
/**
 * 
 * Sanitizes a value to a string using preg_replace().
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizePregReplace extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Applies [[php::preg_replace() | ]] to the value.
     * 
     * @param mixed $value The value to be sanitized.
     * 
     * @param string $pattern The regex pattern to apply.
     * 
     * @param string $replace Replace the found pattern with this string.
     * 
     * @return string The sanitized value.
     * 
     */
    public function __invoke($value, $pattern, $replace)
    {
        // if the value is not required, and is blank, sanitize to null
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);
                
        if ($null) {
            return null;
        }
        
        // normal sanitize
        return preg_replace($pattern, $replace, $value);
    }
}