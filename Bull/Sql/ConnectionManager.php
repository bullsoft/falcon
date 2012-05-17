<?php
/**
 * 
 * This file is part of the Bull Project for PHP.
 * 
 * Manages connections to default, master, and slave databases.
 * 
 * @package Bull.Sql
 * 
 */
class Bull_Sql_ConnectionManager
{
    /**
     * 
     * An SQL adapter factory.
     * 
     * @var AdapterFactory
     * 
     */
    protected $adapter_factory;
    
    /**
     * 
     * SQL adapter connection objects as constructed from their params.
     * 
     * @var array
     * 
     */
    protected $conn = array(
        'default' => null,
        'masters' => array(),
        'slaves'  => array(),
    );
    
    /**
     * 
     * The default connection params.
     * 
     * @var array
     * 
     */
    protected $default = array(
        'adapter'  => null,
        'dsn'      => array(),
        'username' => null,
        'password' => null,
        'options'  => array(),
    );
    
    /**
     * 
     * Params for one or more master connections. The key for each element in
     * the array is a name for the connection, and each value is an array of
     * connection params (cf. the `$default` array elements).
     * 
     * @var array
     * 
     */
    protected $masters = array();
    
    /**
     * 
     * Params for one or more slave connections. The key for each element in
     * the array is a name for the connection, and each value is an array of
     * connection params (cf. the $default array elements).
     * 
     * @var array
     * 
     */
    protected $slaves = array();
    
    /**
     * 
     * Constructor.
     * 
     * @param AdapterFactory $adapter_factory An adapter factory to create 
     * connection objects.
     * 
     * @param array $default An array of key-value pairs for the default
     * connection.
     * 
     * @param array $masters An array of key-value pairs where the key is
     * the connection name and the value is an array of connection params.
     * 
     * @param array $slaves An array of key-value pairs where the key is
     * the connection name and the value is an array of connection params.
     * 
     */
    public function __construct(
        Bull_Sql_AdapterFactory $adapter_factory,
        array $default = array(),
        array $masters = array(),
        array $slaves  = array()
    ) {
        $this->adapter_factory = $adapter_factory;
        $this->setDefault($default);
        foreach ($masters as $name => $params) {
            $this->setMaster($name, $params);
        }
        foreach ($slaves as $name => $params) {
            $this->setSlave($name, $params);
        }
    }
    
    /**
     * 
     * Sets the default connection params.
     * 
     * @param array $params The default connection params.
     * 
     * @return void
     * 
     */
    public function setDefault(array $params)
    {
        $this->default = array_merge($this->default, $params);
    }
    
    /**
     * 
     * Sets the params for one master connection by name.
     * 
     * @param string $name The master connection name.
     * 
     * @param array $params The master connection params.
     * 
     * @return void
     * 
     */
    public function setMaster($name, array $params)
    {
        $this->masters[$name] = $params;
    }
    
    /**
     * 
     * Sets the params for one slave connection by name.
     * 
     * @param string $name The slave connection name.
     * 
     * @param array $params The slave connection params.
     * 
     * @return void
     * 
     */
    public function setSlave($name, array $params)
    {
        $this->slaves[$name] = $params;
    }
    
    /**
     * 
     * Returns a "read" connection.  Picks a connection in this order:
     * 
     * - A random slave; or,
     * 
     * - If there are no slaves, a random master; or,
     * 
     * - If there are no masters, the default connection.
     * 
     * @return AbstractAdapter
     * 
     */
    public function getRead()
    {
        if ($this->slaves) {
            return $this->getSlave();
        } elseif ($this->masters) {
            return $this->getMaster();
        } else {
            return $this->getDefault();
        }
    }
    
    /**
     * 
     * Returns a "write" connection.  Picks a connection in this order:
     * 
     * - A random master; or,
     * 
     * - If there are no masters, the default connection.
     * 
     * @return AbstractAdapter
     * 
     */
    public function getWrite()
    {
        if ($this->masters) {
            return $this->getMaster();
        } else {
            return $this->getDefault();
        }
    }
    
    /**
     * 
     * Returns the default connection object.
     * 
     * @return AbstractAdapter
     * 
     */
    public function getDefault()
    {
        if (! $this->conn['default'] instanceof AbstractAdapter) {
            $this->conn['default'] = $this->adapter_factory->newInstance(
                $this->default['adapter'],
                $this->default['dsn'],
                'master',
                $this->default['username'],
                $this->default['password'],
                $this->default['options']
            );
        }
        return $this->conn['default'];
    }
    
    /**
     * 
     * Returns a "master" connection object by name.
     * 
     * @param string $name The master connection name; if not specified,
     * returns a random master connection.
     * 
     * @return AbstractAdapter
     * 
     */
    public function getMaster($name = null)
    {
        if ($name === null) {
            $name = array_rand($this->masters);
        } elseif (! isset($this->masters[$name])) {
            throw new Bull_Sql_Exception_NoSuchMaster($name);
        }
        
        $is_conn = isset($this->conn['masters'][$name])
                && $this->conn['masters'][$name] instanceof Bull_Sql_AbstractAdapter;
                
        if (! $is_conn) {
            $params = $this->merge($this->default, $this->masters[$name]);
            $this->conn['masters'][$name] = $this->adapter_factory->newInstance(
                $params['adapter'],
                $params['dsn'],
                'master',
                $params['username'],
                $params['password'],
                $params['options']
            );
        }
        
        return $this->conn['masters'][$name];
    }
    
    /**
     * 
     * Returns a "slave" connection object by name.
     * 
     * @param string $name The slave connection name; if not specified,
     * returns a random slave.
     * 
     * @return AbstractAdapter
     * 
     */
    public function getSlave($name = null)
    {
        if ($name === null) {
            $name = array_rand($this->slaves);
        } elseif (! isset($this->slaves[$name])) {
            throw new Bull_Sql_Exception_NoSuchSlave($name);
        }
        
        $is_conn = isset($this->conn['slaves'][$name])
                && $this->conn['slaves'][$name] instanceof Bull_Sql_AbstractAdapter;
        
        if (! $is_conn) {
            $params = $this->merge($this->default, $this->slaves[$name]);
            $this->conn['slaves'][$name] = $this->adapter_factory->newInstance(
                $params['adapter'],
                $params['dsn'],
                'slave',
                $params['username'],
                $params['password'],
                $params['options']
            );
        }
        return $this->conn['slaves'][$name];
    }
    
    /**
     * 
     * A somewhat more friendly merge function thatn array_merge_recursive()
     * (we need to override sequential values, not append them).
     * 
     * @param array $baseline The baseline values.
     * 
     * @param array $override The override values.
     * 
     * @return array
     * 
     */
    protected function merge($baseline, array $override = array())
    {
        foreach ($override as $key => $val) {
            if (array_key_exists($key, $baseline) && is_array($val)) {
                $baseline[$key] = $this->merge($baseline[$key], $override[$key]);
            } else {
                $baseline[$key] = $val;
            }
        }
        
        return $baseline;
    }
}
