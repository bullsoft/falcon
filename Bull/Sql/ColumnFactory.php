<?php
/**
 * 
 * This file is part of the Bull Project for PHP.
 * 
 * A factory for column objects.
 * 
 * @package Bull.Sql
 * 
 */
class Bull_Sql_ColumnFactory
{
    /**
     * 
     * Returns a new Column object.
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
    public function newInstance(
        $name,
        $type, 
        $size,
        $scale,
        $notnull,
        $default,
        $autoinc,
        $primary
    ) {
        return new Bull_Sql_Column(
            $name,
            $type, 
            $size,
            $scale,
            $notnull,
            $default,
            $autoinc,
            $primary
        );
    }
}
