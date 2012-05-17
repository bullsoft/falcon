<?php
/**
 *
 * 单例抽象类(PHP_VERSION >= 5.3.0)
 *
 * @package Bull.Util
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 */
abstract class Bull_Util_Singleton
{
    protected static $instances = array();
    
    protected function __construct($param = array()) {}
    
    public static function getInstance($param = array())
    {
        $class = get_called_class();
        
        if (!isset(self::$instances[$class])) {
            
            self::$instances[$class] = new $class($param);
        }
        
        return self::$instances[$class];
    }
}