<?php
/**
 * 
 * Sanitizes a file-upload information array.
 * 
 * @package Bull.Filter.Adapter
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Filter_Adapter_SanitizeUpload extends Bull_Filter_Adapter_Abstract
{
    /**
     * 
     * Sanitizes a file-upload information array.  If no file has been 
     * uploaded, the information will be returned as null.
     * 
     * @param array $value An array of file-upload information.
     * 
     * @return mixed The sanitized upload information array, or null.
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
        
        // has to be an array
        if (! is_array($value)) {
            return null;
        }
        
        // presorted list of expected keys
        $expect = array('error', 'name', 'size', 'tmp_name', 'type');
        
        // remove unexpected keys
        foreach ($value as $key => $val) {
            if (! in_array($key, $expect)) {
                unset($value[$key]);
            }
        }
        
        // sort the list of remaining actual keys
        $actual = array_keys($value);
        sort($actual);
        
        // make sure the expected and actual keys match up
        if ($expect != $actual) {
            return null;
        }
        
        // if all the non-error values are empty, still null
        $empty = empty($value['name']) &&
                 empty($value['size']) &&
                 empty($value['tmp_name']) &&
                 empty($value['type']);
                 
        if ($empty) {
            return null;
        }
        
        // everything looks ok, return as-is
        return $value;
    }
}