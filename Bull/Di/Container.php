<?php
/**
 * 
 * Di for storing objects, with built-in lazy loading.
 * 
 * @package Bull
 * 
 */
class Bull_Di_Container
{
    /**
     * 
     * Map of registry names to object instances (or their specs for on-demand 
     * creation).
     * 
     * @var array
     * 
     */
    protected static $_obj = array();
    
    /**
     * 
     * Constructor is disabled to enforce a singleton pattern.
     * 
     */
    final private function __construct() {}
    
    /**
     * 
     * Accesses an object in the registry.
     * 
     * @param string $name The registered name.
     * 
     * @return object The object registered under $name.
     * 
     * @todo Localize these errors.
     * 
     */
    public static function get($name)
    {
        // has the shared object already been loaded?
        if (! Bull_Di_Container::has($name)) {
            throw new Bull_Di_Exception('ERR_NOT_IN_REGISTRY');
        }
        
        // was the registration for a lazy-load?
        if (Bull_Di_Container::$_obj[$name] instanceof Bull_Di_Lazy) {
            $call = Bull_Di_Container::$_obj[$name];
            Bull_Di_Container::$_obj[$name] = $call();
        }
        
        // done
        return Bull_Di_Container::$_obj[$name];
    }
    
    /**
     * 
     * Registers an object under a unique name.
     * 
     * @param string $name The name under which to register the object.
     * 
     * @param object $value The registry specification.
     * 
     * @return void
     * 
     */
    public static function set($name, $value)
    {
        if (Bull_Di_Container::has($name)) {
            // name already exists in registry
            throw new Bull_Di_Exception('ERR_REGISTRY_NAME_EXISTS');
        }

        if (!is_object($value)) {
            throw new Bull_Di_Exception("ERR_REGISTRY_FAILURE");
        }

        // register as an object, or as a class and config?
        if ($value instanceof Closure) {
            // register a class and config for lazy loading
            $value = new Bull_Di_Lazy($value);
        }
        
        Bull_Di_Container::$_obj[$name] = $value;
    }
    
    /**
     * 
     * Check to see if an object name already exists in the registry.
     * 
     * @param string $name The name to check.
     * 
     * @return bool
     * 
     */
    public static function has($name)
    {
        return ! empty(Bull_Di_Container::$_obj[$name]);
    }

    /**
     *
     * 代码翻译，形式：{{php return ... }}
     *
     * @param $code string 如果以{{php开头，并以}}结尾，则执行它，如果不是则直接返回
     *
     *
     */
    public static function translate($code)
    {
        if (is_string($code)) {
            $code = trim($code);
            $opentag  = '{{php';
            $closetag = '}}';
            $olen = strlen($opentag);
            $clen = strlen($closetag);
        
            if (substr($code, 0, $olen) == $opentag
                && substr($code, -1 * $clen) == $closetag) {
                $code = eval(substr($code, $olen, -1 * $clen));
            }
        }
        return $code;
    }

    public static function newInstance($class, $params=array(), $setters=array())
    {
        $class = trim($class);
        $param  = Bull_Di_Container::get('config')->get("class.". $class .".params");
        $setter = Bull_Di_Container::get('config')->get("class.". $class .".setters");
        
        $params = array_merge((array) $param, $params);
        
        // lazy-load params as needed
        foreach ($params as $key => & $val) {
            $val = Bull_Di_Container::translate($val);
            if ($val instanceof Bull_Di_Lazy) {
                $val = $val();
            }
        }
        
        // merge the setters
        $setters = array_merge((array) $setter, $setters);
        
        // create the new instance
        $call = array(new ReflectionClass($class), 'newInstance');
        
        $object = call_user_func_array($call, $params);
        
        // call setters after creation
        foreach ($setters as $method => $value) {
            $value = Bull_Di_Container::translate($value);
            // does the specified setter method exist?
            if (method_exists($object, $method)) {
                // lazy-load values as needed
                if ($value instanceof Bull_Di_Lazy) {
                    $value = $value();
                }
                // call the setter
                $object->$method($value);
            }
        }
        
        // done!
        return $object;
    }
}
