<?php
/**
 * 
 * Sanitizes a value to an ISO-8601 date.
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeIsoDate extends Bull_Filter_Adapter_SanitizeIsoTimestamp
{
    /**
     * 
     * Forces the value to an ISO-8601 formatted date ("yyyy-mm-dd").
     * 
     * @param string $value The value to be sanitized.  If an integer, it
     * is used as a Unix timestamp; otherwise, converted to a Unix
     * timestamp using [[php::strtotime() | ]].
     * 
     * @return string The sanitized value.
     * 
     */
    public function __invoke($value)
    {
        // look for Ymd keys?
        if (is_array($value)) {
            $value = $this->_arrayToDate($value);
        }
        
        // if the value is not required, and is blank, sanitize to null
        $null = ! $this->objManager->getRequire() &&
            $this->objManager->validateBlank($value);
                
        if ($null) {
            return null;
        }
        
        // normal sanitize
        $format = 'Y-m-d';
        if (is_int($value)) {
            return date($format, $value);
        } else {
            return date($format, strtotime($value));
        }
    }
}