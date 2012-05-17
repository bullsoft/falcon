<?php
/**
 * 
 * This file is part of the Bull Project for PHP.
 * 
 * Represents one column from a table.
 * 
 * @package Bull.Sql
 * 
 */
class Bull_Sql_Column
{
    /**
     * 
     * The name of the column.
     * 
     * @var string
     * 
     */
    protected $name;
    
    /**
     * 
     * The datatype of the column.
     * 
     * @var string
     * 
     */
    protected $type; 
    
    /**
     * 
     * The size of the column; typically, this is a number of bytes or 
     * characters for the column as a whole.
     * 
     * @var int
     * 
     */
    protected $size;
    
    /**
     * 
     * The scale of the column (i.e., the number of decimal places).
     * 
     * @var int
     * 
     */
    protected $scale;
    
    /**
     * 
     * Is the column marked as `NOT NULL`?
     * 
     * @var bool
     * 
     */
    protected $notnull;
    
    /**
     * 
     * The default value of the column.
     * 
     * @var mixed
     * 
     */
    protected $default;
    
    /**
     * 
     * Is the column auto-incremented?
     * 
     * @var bool
     * 
     */
    protected $autoinc;
    
    /**
     * 
     * Is the column part of the primary key?
     * 
     * @var bool
     * 
     */
    protected $primary;
    
    /**
     * 
     * Constructor.
     * 
     * @param string $name The name of the column.
     * 
     * @param string $type The datatype of the column.
     * 
     * @param int $size The size of the column.
     * 
     * @param int $scale The scale of the column (i.e., the number of digits
     * after the decimal point).
     * 
     * @param mixed $default The default value of the column.
     * 
     * @param bool $autoinc Is the column auto-incremented?
     * 
     * @param bool $primary Is the column part of the primary key?
     * 
     */
    public function __construct(
        $name,
        $type, 
        $size,
        $scale,
        $notnull,
        $default,
        $autoinc,
        $primary
    ) {
        $this->name     = $name;
        $this->type     = $type; 
        $this->size     = $size;
        $this->scale    = $scale;
        $this->notnull  = (bool) $notnull;
        $this->default  = $default;
        $this->autoinc  = (bool) $autoinc;
        $this->primary  = (bool) $primary;
    }
    
    /**
     * 
     * Returns property values.
     * 
     * @param string $key The property name.
     * 
     * @return mixed The property value.
     * 
     */
    public function __get($key)
    {
        return $this->$key;
    }

    public static function __set_state($array)
    {
        $column = new Bull_Sql_Column(
            $array['name'],
            $array['type'],
            $array['size'],
            $array['scale'],
            $array['notnull'],
            $array['default'],
            $array['autoinc'],
            $array['primary']
        );
        return $column;
    }
}
