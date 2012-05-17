<?php
abstract class Bull_View_Abstract
{
    protected $data;
    
    protected $accept;

    protected $format_types;
    
    protected $format;

    protected $path;

    protected $cache = false;
    
    public function __construct()
    {
        $this->format_types = new Bull_View_FormatTypes();
    }
     
    public function setCache($cache = false)
    {
        $this->cache = $cache;
    }
    
    public function setFormat($format)
    {
        $this->format = $format;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    public function getFormat()
    {
        return $this->format;
    }
    
    public function getContentType()
    {
        return $this->format_types->getContentType($this->format);
    }
    
    public function setDatas(array $data)
    {
        $this->data = $data;
    }

    public function setData($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    public function renderString($str) {}

    public function render($action) {}
    
    public function displayString($str) {}
    
    public function display($action) {}
}
