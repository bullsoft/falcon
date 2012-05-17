<?php
/**
 * 
 * This file is part of the Bull Project for PHP.
 * 
use PDOStatement;

/**
 * 
 * Interface for query profilers.
 * 
 * @package Bull.Sql
 * 
 */
interface Bull_Sql_ProfilerInterface
{
    /**
     * 
     * Turns the profiler on and off.
     * 
     * @param bool $active True to turn on, false to turn off.
     * 
     * @return void
     * 
     */
    public function setActive($active);
    
    /**
     * 
     * Is the profiler active?
     * 
     * @return bool
     * 
     */
    public function isActive();
    
    /**
     * 
     * Executes a PDOStatment and profiles it.
     * 
     * @param PDOStatement $stmt The PDOStatement to execute and profile.
     * 
     * @param array $data The data that was bound into the statement.
     * 
     * @return mixed
     * 
     */
    public function exec(PDOStatement $stmt, array $data = array());
    
    /**
     * 
     * Calls a user function and and profile it.
     * 
     * @param callable $func The user function to call.
     * 
     * @param array $data The data that was used by the function.
     * 
     * @return mixed
     * 
     */
    public function call($func, $text, array $data = array());
    
    /**
     * 
     * Adds a profile to the profiler.
     * 
     * @param string $text The text (typically an SQL query) being profiled.
     * 
     * @param float $time The elapsed time in seconds.
     * 
     * @param array $data The data that was used.
     * 
     * @return mixed
     * 
     */
    public function addProfile($text, $time, array $data = array());
    
    /**
     * 
     * Returns all the profiles.
     * 
     * @return array
     * 
     */
    public function getProfiles();
}
