<?php
/**
 * 
 * Log handler to ignore all messages.
 * 
 * @package Bull.Log
 * 
 * @author Gu Weigang <guweigang@baidu.com>
 * 
 */
class Bull_Log_None extends Bull_Log_Abstract
{
    /**
     * 
     * Support method to save (write) an event and message to the log.
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
        return true;
    }
}
