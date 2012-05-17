<?php

class Bull_Parse_Yaml extends Bull_Parse_Abstract
{
    public function load($file)
    {
        $realfile = Bull_Util_File::exists($file);
        
        if (!$realfile) {
            throw new Bull_Parse_Exception("File: {$file} Not exists.");
        }
        
        $this->configs = Bull_Parse_Spyc::YAMLLoad($realfile);
        
        return $this;
    }
}