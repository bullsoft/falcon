<?php
/**
 * 
 * Sanitizes a value to an integer.
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeInt extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Forces the value to an integer.
     * 
     * Attempts to extract a valid integer from the given value, using an
     * algorithm somewhat less naive that "remove all characters that are not
     * '0-9+-'".  The result may not be expected, but it will be a integer.
     * 
     * @param mixed $value The value to be sanitized.
     * 
     * @return int The sanitized value.
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
        
        // sanitize numerics and non-strings
        if (! is_string($value) || is_numeric($value)) {
            // we double-cast here to honor scientific notation.
            // (int) 1E5 == 1, but (int) (float) 1E5 == 100000
            return (int) (float) $value;
        }
        
        // it's a non-numeric string, attempt to extract an integer from it.
        
        // remove all chars except digit and minus.
        // this removes all + signs; any - sign takes precedence because ...
        //     0 + -1 = -1
        //     0 - +1 = -1
        // ... at least it seems that way to me now.
        $value = preg_replace('/[^0-9-]/', '', $value);
        
        // remove all trailing minuses
        $value = rtrim($value, '-');
        
        // pre-empt further checks if already empty
        if ($value == '') {
            return (int) $value;
        }
        
        // remove all minuses not at the front
        $is_negative = ($value[0] == '-');
        $value = str_replace('-', '', $value);
        if ($is_negative) {
            $value = '-' . $value;
        }
        
        // looks like we're done
        return (int) $value;
    }
}