<?php

class Bull_Http_Exception_HeadersSent extends Bull_Http_Exception
{
    /**
     * 
     * Constructor.
     * 
     * @param string $file The file from which headers were first sent.
     * 
     * @param int $line The line number in that file where headers were sent.
     * 
     */
    public function __construct($file, $line)
    {
        $message = "Headers have already been sent from '{$file}' at line {$line}.";
        parent::__construct($message);
    }
}