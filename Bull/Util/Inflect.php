<?php
/**
 * 
 * Methods for inflecting strings from one form to another form.
 * 
 * @package Bull.Util
 * 
 */
class Bull_Util_Inflect
{
    /**
     * 
     * Returns "camelCapsWord" and "CamelCapsWord" as "camel-caps-word".
     * 
     * @param string $str The camel-caps word.
     * 
     * @return string The word with dashes in place of camel caps.
     * 
     */
    public function camelToDashes($str)
    {
        $str = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
        $str = str_replace(' ', '-', ucwords($str));
        return strtolower($str);
    }
    
    /**
     * 
     * Returns "camelCapsWord" and "CamelCapsWord" as "Camel_Caps_Word".
     * 
     * @param string $str The camel-caps word.
     * 
     * @return string The word with underscores in place of camel caps.
     * 
     */
    public function camelToUnder($str)
    {
        $str = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
        $str = str_replace(' ', '_', ucwords($str));
        return $str;
    }
    
    /**
     * 
     * PSR-0 class-to-filename implementation.
     * 
     * @param string $spec The class or interface name.
     * 
     * @return string The spec as converted to a file path.
     * 
     */
    public function classToFile($spec, $extension='.php')
    {
         return str_replace('_', DIRECTORY_SEPARATOR, $spec) . $extension;
    }
    
    /**
     * 
     * Splits a fully-qualified class name into namespace and class portions.
     *
     * Convert Bull_Util_Inflect to array("Bull_Util", "Infletct")
     *
     * @param string $spec The fully-qualified class name.
     * 
     * @return array A 2-element array where element 0 is the namespace and
     * element 1 is the class.
     * 
     */
    public function splitClass($spec)
    {
        // look for last namespace separator
        $pos = strrpos($spec, '_');
        if ($pos === false) {
            $prefix = null;
            $class  = $spec;
        } else {
            $prefix   = substr($spec, 0, $pos);
            $filename = substr($spec, $pos + 1);
        }
        
        return array($prefix, $filename);
    }
    
    /**
     * 
     * Returns "foo-bar-baz" as "fooBarBaz".
     * 
     * @param string $str The dashed word.
     * 
     * @return string The word in camelCaps.
     * 
     */
    public function dashesToCamel($str)
    {
        $str = ucwords(str_replace('-', ' ', $str));
        $str = str_replace(' ', '', $str);
        return lcfirst($str);
    }
    
    /**
     * 
     * Returns "foo-bar-baz" as "foo_bar_baz".
     * 
     * @param string $str The underscore word.
     * 
     * @return string The word with dashes.
     * 
     */
    public function dashesToUnder($str)
    {
        return str_replace('-', '_', $str);
    }
    
    /**
     * 
     * Returns "foo-bar-baz" as "FooBarBaz".
     * 
     * @param string $str The dashed word.
     * 
     * @return string The word in StudlyCaps.
     * 
     */
    public function dashesToStudly($str)
    {
        return ucfirst($this->dashesToCamel($str));
    }
    
    /**
     * 
     * Returns "foo_bar_baz" as "fooBarBaz".
     * 
     * @param string $str The underscore word.
     * 
     * @return string The word in camelCaps.
     * 
     */
    public function underToCamel($str)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', $str);
        return lcfirst($str);
    }
    
    /**
     * 
     * Returns "foo_bar_baz" as "foo-bar-baz".
     * 
     * @param string $str The underscore word.
     * 
     * @return string The word with dashes.
     * 
     */
    public function underToDashes($str)
    {
        return str_replace('_', '-', $str);
    }
    
    /**
     * 
     * Returns "foo_bar_baz" as "FooBarBaz".
     * 
     * @param string $str The underscore word.
     * 
     * @return string The word in StudlyCaps.
     * 
     */
    public function underToStudly($str)
    {
        return ucfirst($this->underToCamel($str));
    }
}
