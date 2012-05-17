<?php
/**
 * 
 * Log handler for appending to a file.
 * 
 * @package Bull.Log
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Log_File extends Bull_Log_Abstract
{
    /** 
     * String format The line format for each saved event.
     * Use '%t' for the timestamp, '%c' for the class name, '%e' for
     * the event type, '%m' for the event description, and '%%' for a
     * literal percent.  Default is '%t %c %e %m'.
     * 
     * @var string
     * 
     */
    protected $_format = '%t %c %e %m',
    
    /**
     * 
     * The file where events should be logged;
     * for example '/www/username/logs/solar.log'.
     *
     * 
     * @var string
     * 
     */
    protected $_file = '';
    
    /**
     * 
     * Post-construction tasks to complete object construction.
     * 
     * @return void
     * 
     */
    protected function __construct($file, $format='%t %c %e %m')
    {
        parent::__construct();
        $this->_file   = $file;
        $this->_format = $format;
    }
    
    /**
     * 
     * Support method to save (write) an event and message to the log.
     * 
     * Appends to the file, and uses an exclusive lock (LOCK_EX).
     * 
     * @param string $class The class name reporting the event.
     * 
     * @param string $event The event type (for example 'info' or 'debug').
     * 
     * @param string $descr A description of the event. 
     * 
     * @return mixed Boolean false if the event was not saved (usually
     * because it was not recognized), or a non-empty value if it was
     * saved.
     * 
     */
    protected function _save($class, $event, $descr)
    {
        $text = str_replace(
            array('%t', '%c', '%e', '%m', '%%'),
            array($this->_getTime(), $class, $event, $descr, '%'),
            $this->_format
        ) . "\n";
    
        return file_put_contents($this->_file, $text, FILE_APPEND | LOCK_EX);
    }
}
