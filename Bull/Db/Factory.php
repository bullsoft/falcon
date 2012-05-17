<?php
/**
 * 数据库模型工厂类
 *
 * @author Gu Weigang <guweigang@baidu.com>
 *
 * @package Bull.Db
 *
 */

class Bull_Db_Factory
{
    /* 数据库配置 */
    static private $db_conn   = array();

    /* 主从库数据库对象 */
    private static $slave     = array();
    private static $master    = array();
    
    private function __construct() {}

    /**
     *
     * 根据配置节加载数据库配置
     *
     * @param $name string 数据库配置节
     *
     * @param $config array 数据库与置详情
     *
     * @return object Bull_Sql_ConnectionManager
     *
     */
    static public function newConnection($name, $config)
    {
        if (!isset(self::$db_conn[$name]))
        {
            $default = array("adapter" => "mysql");
            $masters = array();
            $slaves  = array();
        
            if (isset($config["default"]))
            {
                $default = $config['default'];
            }

            if (isset($config["master"]))
            {
                foreach($config["master"] as $key => $master)
                {
                    $masters[$key] = $master;
                }
            }

            if (isset($config["slave"]))
            {
                foreach($config["slave"] as $key => $slave)
                {
                    $slaves[$key] = $slave;
                }
            }
            
            $sql_adapter = new Bull_Sql_AdapterFactory();
        
            $conn = new Bull_Sql_ConnectionManager($sql_adapter,
                                                   $default,
                                                   $masters,
                                                   $slaves);
            self::$db_conn[$name] = $conn;
        }

        return self::$db_conn[$name];
    }
    
    /**
     *
     * 获取配置池
     *
     * @param $name 数据库配置节
     *
     * @return object Bull_Sql_ConnectionManager
     *
     */
    static public function getConnection($name = null)
    {
        if ($name === null)
        {
            return self::$db_conn;
        } else if (isset(self::$db_conn[$name])) {
            return self::$db_conn[$name];
        } else {
            throw new Bull_Db_Exception_NoSuchConf($name);
        }
    }

    /**
     *
     * 获取数据库主库对象
     *
     * @parma $name string
     *
     * @param $index mixed
     *
     * @return object Bull_Sql_Adapter_Abstract
     *
     */
    static public function getMaster($name, $index = null)
    {
        if (!isset(self::$master[$name]))
        {
            $conn_manager = self::getConnection($name);
        
            if ($index === null)
            {
                self::$master[$name] = $conn_manager->getWrite();
            } else {
                self::$master[$name] = $conn_manager->getMaster($index);
            }
        }
        
        return self::$master[$name];
    }

    /**
     *
     * 获取数据库从库对象
     *
     * @parma $name string
     *
     * @param $index mixed
     *
     * @return object Bull_Sql_Adapter_Abstract
     *
     */
    static public function getSlave($name, $index = null)
    {
        if (!isset(self::$slave[$name]))
        {
            $conn_manager = self::getConnection($name);
 
            if ($index === null)
            {
                self::$slave[$name] = $conn_manager->getRead();
            } else {
                self::$slave[$name] = $conn_manager->getSlave($index);
            }
       
        }
       
        return self::$slave[$name];   
    }
}
