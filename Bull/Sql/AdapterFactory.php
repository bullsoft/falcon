<?php
/**
 * 
 * This file is part of the Bull Project for PHP.
 * 
 * A factory for adapter objects.
 * 
 * @package Bull.Sql
 * 
 */
class Bull_Sql_AdapterFactory
{
    /**
     * 
     * A map of short adapter names to fully-qualified classes.
     * 
     * @var array
     * 
     */
    protected $map = array(
        'mysql'  => 'Bull_Sql_Adapter_Mysql',
        'pgsql'  => 'Bull_Sql_Adapter_Pgsql',
        'sqlite' => 'Bull_Sql_Adapter_Sqlite',
    );
    
    /**
     * 
     * Constructor.
     * 
     * @param array $map An override map of adapter names to classes.
     * 
     */
    public function __construct(array $map = array())
    {
        $this->map = array_merge($this->map, $map);
    }
    
    /**
     * 
     * Returns a new adapter instance.
     * 
     * @param string $name The name of the adapter.
     * 
     * @param mixed $dsn The DSN for the adapter.
     * 
     * @param string $username The username for the adapter.
     * 
     * @param string $password The password for the adapter.
     * 
     * @param array $options PDO options for the adapter.
     * 
     * @return AbstractAdapter
     * 
     */
    public function newInstance(
        $name,
        $dsn,
        $type,
        $username = null,
        $password = null,
        $options = array()
    ) {
        $class = $this->map[$name];
        $profiler = new Bull_Sql_Profiler;
        $column_factory = new Bull_Sql_ColumnFactory;
        $select_factory = new Bull_Sql_SelectFactory;
        return new $class(
            $profiler,
            $column_factory,
            $select_factory,
            $dsn,
            $type,
            $username,
            $password,
            $options
        );
    }
}
