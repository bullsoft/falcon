<?php
/**
 * 
 * Log handler to echo messages directly.
 * 
 * @package Bull.Log
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Log_Echo extends Bull_Log_Abstract
{
    /**
     * 
     * String format The line format for each saved event.
     * Use '%t' for the timestamp, '%c' for the class name, '%e' for
     * the event type, '%m' for the event description, and '%%' for a
     * literal percent.  Default is '%t %c %e %m'.
     * 
     * @var string
     *
     */
    protected $_format = '%t %c %e %m';

    /**
     * String output Output mode.  Set to 'html' for HTML; 
     * or 'text' for plain text.  Default autodetects by SAPI version.
     *
     * @var string|null
     *
     */
    protected $_output = null;

    
    public function __construct($events = '*', $format = '%t %c %e %m')
    {
        parent::__construct($events);
        $this->_format = $format;
    }
    
    /**
     * 
     * Modifies $this->_config after it has been built.
     * 
     * @return void
     * 
     */
    protected function postConstruct()
    {
        if (empty($this->_output)) {
            $mode = (PHP_SAPI == 'cli') ? 'text' 
                                        : 'html';
            $this->_output = $mode;
        }
    }
    
    /**
     * 
     * Echos the log message.
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
        );
        
        if (strtolower($this->_output) == 'html') {
            $text = htmlspecialchars($text) . '<br />';
        } else {
            $text .= PHP_EOL;
        }
        echo $text;
        return true;
    }
}
