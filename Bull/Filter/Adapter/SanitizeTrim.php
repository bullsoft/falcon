<?php
/**
 * 
 * Sanitizes a value to a string using trim().
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeTrim extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Trims characters from the beginning and end of the value.
     * 
     * @param mixed $value The value to be sanitized.
     * 
     * @param string $chars Trim these characters (default space).
     * 
     * @return string The sanitized value.
     * 
     */
    public function __invoke($value, $chars = ' ')
    {
        // if the value is not required, and is blank, sanitize to null
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);
                
        if ($null) {
            return null;
        }
        
        // normal sanitize
        return trim($value, $chars);
    }
}