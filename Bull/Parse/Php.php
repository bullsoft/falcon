<?php

class Bull_Parse_Php extends Bull_Parse_Abstract
{
    public function load($file)
    {
        $realfile = Bull_Util_File::exists($file);
        
        if (!$realfile) {
            throw new Bull_Parse__Exception("File: {$file} Not exists.");
        }
        include $realfile;
        unset($realfile, $file);
        
        $vars = get_defined_vars();
        foreach ($vars as $key => $val) {
            if ($key == 'this') {
                continue;
            }
            $this->configs[$key] = $val;
        }
        
        return $this;
    }
}