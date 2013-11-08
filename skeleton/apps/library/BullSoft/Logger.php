<?php
namespace BullSoft;
class Logger
{
    protected $logger;
    protected $filepath;
    protected $template;
    
    public function __construct($filepath)
    {
        $dir = dirname($filepath);
        if(!file_exists($dir)) {
            try {
                mkdir($dir, 0777, true);
            } catch(\Exception $e) {
                error_log("permission denied for creating directory, {$dir}");
                throw $e;
            }
        }
        $this->filepath = $filepath;
    }

    public function setFormat($template)
    {
        $this->template = $template;
    }

    public function __call($method, $args)
    {
        $methods = array(
            "special", "custom", "debug", "info", "notice", "warning", "error", "alert", "critical", "emergence"
        );
        if (!in_array($method, $methods)) {
            throw new \BadMethodCallException("method {$method} not exists");
        }
        
        if (count($args) < 1) {
            throw new \InvalidArgumentException("at least 1 parameters");
        }
        
        $message = array_shift($args);
        $type = strtoupper($method);
        $this->log($message, constant('Phalcon\Logger::'.$type), $args);
    }
    
    public function log($message, $type, $args=array())
    {
        $errorTypes = array(
            \Phalcon\Logger::WARNING, \Phalcon\Logger::ERROR, \Phalcon\Logger::ALERT, \Phalcon\Logger::CRITICAL, \Phalcon\Logger::EMERGENCE
        );
        if(in_array($type, $errorTypes, true)) {
            $this->logger = new \Phalcon\Logger\Adapter\File($this->filepath.".wf");
        } else {
            $this->logger = new \Phalcon\Logger\Adapter\File($this->filepath);
        }
        
        $trace = debug_backtrace();
        $depth = count($trace)>1?1:0;
        $current = $trace[$depth];
        $file  = basename($current['file']);
        $line  = $current['line'];
        $ip    = \Utility::getIP();
        unset($trace, $current);
        $message = preg_replace('/%(\w+)%/e', '$\\1', $this->template);
        if (!empty($args)) {
            $message .= PHP_EOL;
            foreach($args as $arg) {
                $message .= $this->logVar($arg);
            }
        }
        $this->logger->log($message, $type);
    }

    public function logVar($var, $varname="var", $level=0)
    {
        $message = "";
        $indentchars = "....";
        
        switch(true) {
            case is_string($var):
                $message .= '<string> $'."{$varname} = ";
                if ($var === null) {
                    $var = "NULL";
                }
                $message .= '"'.$var.'"';
                $message .=  PHP_EOL;
                break;
                
            case is_bool($var):
                $message .= '<boolean> $'."{$varname} = ";
                if ($var === TRUE) {
                    $message .= "TRUE";
                } else {
                    $message .= "FALSE";
                }
                $message .= PHP_EOL;
                break;
                
            case is_array($var):
                $message .= '<array> $'. "{$varname} = (".PHP_EOL;
                $msgend = str_repeat($indentchars, $level);
                $indent = str_repeat($indentchars, $level+1);
                $keys = array_keys($var);
                foreach($keys as $key) {
                    $val = $var[$key];
                    $message .= $indent . $this->logVar($val, $key, $level+1);
                }
                $message .= $msgend . ')'.PHP_EOL;
                break;
                
            case is_object($var):
                $message .= '<object> '.get_class($var).'::$'."{$varname} = {" . PHP_EOL;
                $msgend = str_repeat($indentchars, $level);
                $indent = str_repeat($indentchars, $level+1);
                $objVars = get_object_vars($var);
                foreach($objVars as $objVarName => $objVarVal) {
                    $message .= $indent . $this->logVar($objVarVal, $objVarName, $level+1);
                }
                $message .= $msgend. "}".PHP_EOL;
                break;
                
            default:
                $message .= '<'.gettype($var).'> $'. "{$varname} = ".$var . PHP_EOL;
                break;
        }
        return $message;
    }
}

/* Logger.php ends here */